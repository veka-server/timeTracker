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

                let error_msg = '';
                if(response.error_msg !== undefined && response.error_msg.length > 0){
                    error_msg = response.error_msg;
                } else {
                    error_msg = Trad.generic_error;
                }

                const event = $.Event("Tableau::show_error_inside_tableau");
                event.msg = error_msg;
                $(table).trigger( event );
                return ;
            }

            callback(response)

        }, 'json').fail(function(response) {

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

                if(response.error_msg !== undefined && response.error_msg.length > 0){
                    Popin.alert(response.error_msg);
                } else {
                    Popin.alert(Trad.generic_error);
                }
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

            Popin.alert(Trad.generic_error);

        }).always(function() {
            $(button).removeAttr('disabled');
            $(button).removeClass('button_loading');
        });

    };
}( jQuery ));
