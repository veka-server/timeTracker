/**
 * Classe de requete ajax post avec support des erreurs, des redirections, ...
 */
(function ( $ ) {

    $.fn.getErrorMsgFromResponse = function() {
        let response = $(this)[0];
        if(response.success !== false) {
            return '';
        }
        let error_msg = '';
        if(response.error_msg !== undefined && response.error_msg.length > 0){
            error_msg = response.error_msg;
        } else {
            error_msg = Trad.generic_error;
        }
        return error_msg ;
    };

    $.fn.getSuccessMsgFromResponse = function() {
        let response = $(this)[0];

        let success = {};
        if(response.success_msg !== undefined && response.success_msg.length > 0){
            success.content = response.success_msg;
        } else if(response.html !== undefined && typeof response.html === "string" && response.html.length > 0){
            success.content = response.html;
        } else {
            success.content = Trad.generic_success_msg;
        }

        if(response.success_titre !== undefined && response.success_titre.length > 0){
            success.titre = response.success_titre;
        } else {
            success.titre = Trad.generic_success_title_popin;
        }

        return success;
    };

    $.fn.postForTableau = function(url, data, callback) {

        let table = $(this);
        $(table).closest('.table_wrapper').scrollTop(0);
        $(table).closest('.table_wrapper').scrollLeft(0);
        $(table).closest('.table_wrapper').addClass('loading');

        return $.post( url, data, function( response ) {

            let error_msg = $(response).getErrorMsgFromResponse();
            if(error_msg.length > 0){

                const event = $.Event("Tableau::show_error_inside_tableau");
                event.msg = error_msg;
                $(table).trigger( event );
                return ;
            }

            callback(response)

        }, 'json').fail(function(response) {

            if(response.statusText === 'abort'){
                return ;
            }

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

            let error_msg = $(response).getErrorMsgFromResponse();
            if(error_msg.length > 0){
                Popin.alert(error_msg);
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

    $.fn.postForm = function(callback) {

        let form = $(this);
        $(form).closest('.popin-container').addClass('loading');

        let url = form.attr('action');

        let data = {};
        $(form).find(':input').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });

        return $.post( url, data, function( response ) {

            let error_msg = $(response).getErrorMsgFromResponse();
            if(error_msg.length > 0){

                Popin.alert(error_msg);

                const event = $.Event("Form::show_error");
                event.msg = error_msg;
                $(form).trigger( event );
                return ;
            }

            let success = $(response).getSuccessMsgFromResponse();
            Popin.alert(success.content, success.titre)

            if(typeof callback === "function"){
                callback(response)
            }

        }, 'json').fail(function(response) {

            Popin.alert(Trad.generic_error);

            const event = $.Event("Form::show_error");
            event.msg = 'Error '+response.status+' : '+response.statusText;
            $(form).trigger( event );

        }).always(function() {

            const event = $.Event("Form::complete");
            event.id = $(form).attr('id');
            $(form).trigger( event );

            $(form).closest('.popin-container').removeClass('loading');
        });
    };

    $.fn.postForContrainte = function(url, data) {

        let input = $(this);
        $(input).closest('.global_input').addClass('input_loading');

        return $.post( url, data, function( response ) {

            let error_msg = $(response).getErrorMsgFromResponse();
            if(error_msg.length > 0){
                Popin.alert(error_msg);
                return ;
            }

            /**  si deja en rouge alors on ne change pas le statut */
            if($(input).closest('.global_input').attr((new Validation()).flag_error) !== undefined){
                return ;
            }

            new Validation().showErrorStatut(input, {
                status: response.contrainte_failed
                , msg: response.contrainte_msg
            }) ;

        }, 'json').fail(function(response) {
            if(response.statusText === "abort"){
                return ;
            }
            Popin.alert(Trad.generic_error);
        }).always(function() {
            $(input).closest('.global_input').removeClass('input_loading');
        });

    };

}( jQuery ));
