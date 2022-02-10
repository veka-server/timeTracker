/**
 * Classe de requete ajax post avec support des erreurs, des redirections, ...
 */
(function ( $ ) {
    $.fn.postForTableau = function(url, data, callback) {

        let table = $(this);
        $(table).closest('.table_wrapper').scrollTop(0);
        $(table).closest('.table_wrapper').scrollLeft(0);
        $(table).closest('.table_wrapper').addClass('loading');

        return $.post( url, data, function( response ) {

            if(response.success === false){
                const event = $.Event("Tableau::show_error_inside_tableau");
                event.msg = response.error_msg;
                $(table).trigger( event );
                return ;
            }

            callback(response)

        }, 'json').fail(function(response) {

            /** @todo show error with popin */

            const event = $.Event("Tableau::show_error_inside_tableau");
            event.msg = 'Error '+response.status+' : '+response.statusText;
            $(table).trigger( event );

        }).always(function() {

            const event = $.Event("Tableau::complete");
            event.id = $(table).attr('id');
            $(table).trigger( event );

            $(table).closest('.table_wrapper').removeClass('loading');
        });

    };

    $.fn.postForExport = function(url, data) {

        let button = $(this);
        $(button).attr('disabled','disabled');
        $(button).addClass('button_loading');

        return $.post( url, data, function( response ) {

            if(response.success === false){
                /** @todo show error with popin */
                console.log('error export')
                return ;
            }

            let element = document.createElement('a');
            element.setAttribute('href', response.header + encodeURIComponent(response.text));
            element.setAttribute('download', response.filename);
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);

        }, 'json').fail(function(response) {

            /** @todo show error with popin */

        }).always(function() {
            $(button).removeAttr('disabled');
            $(button).removeClass('button_loading');
        });

    };
}( jQuery ));
