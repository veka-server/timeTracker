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

        }, 'json').always(function() {

            const event = $.Event("Tableau::complete");
            event.id = $(table).attr('id');
            $(table).trigger( event );

            $(table).closest('.table_wrapper').removeClass('loading');
        });

    };
}( jQuery ));
