/**
 * Classe de error handling
 */

class ErrorHandling {

    constructor() {
        this.catchAllConsoleLog();
        this.catchJqueryAjaError();
        this.catchJSError();
    }

    // redefine a new console
    catchAllConsoleLog = function(){
        const current_instance = this;
        window.console = (function(oldCons){
            return {
                log: function(text){
                    oldCons.log(text);
//                    current_instance.sendErrorToServer(text, 'log')
                },
                info: function (text) {
                    oldCons.info(text);
//                    current_instance.sendErrorToServer(text, 'info')
                },
                warn: function (text) {
                    oldCons.warn(text);
                    current_instance.sendErrorToServer(text, 'warn')
                },
                error: function (text) {
                    oldCons.error(text);
                    current_instance.sendErrorToServer(text, 'error')
                }
            };
        }(window.console));
    }

    // catch jquery AJAX error
    catchJqueryAjaError = function(){
        const current_instance = this;
        $( document ).ajaxError(function(event, XHRObject, settings){
            var message = [
                'ERROR '+XHRObject.status+' : '+XHRObject.statusText,
                'URL: ' + settings.url
            ].join("\r\n");
            current_instance.sendErrorToServer(message, 'error')
            return false;
        });
    }

    catchJSError = function() {
        const current_instance = this;
        window.onerror = function (msg, url, lineNo, columnNo, error) {
            var message = [
                msg,
                'URL: ' + url,
                'Line: ' + lineNo,
                'Column: ' + columnNo,
                'Error object: ' + JSON.stringify(error)
            ].join("\r\n");
            current_instance.sendErrorToServer(message, 'error')
            return false;
        };
    }

    // envoyer l'erreur au server PHP
    sendErrorToServer = function(error, level){
        try{

            let data = {level:level};
            if(typeof error === 'string'){
                data.error = error;
            }else if(error.constructor.name == 'ReferenceError'){
                data.error = 'ReferenceError : '+error.message;
            }else if(typeof error.responseJSON == 'object'){
                data.error = error.JSON.stringify(error.responseJSON);
            }else {
                data.error = 'AjaxError : ERROR '+error.status+' : '+error.statusText;
            }

            $.ajax({
                url: '/error_js_caught',
                method: 'POST',
                data: data,
                global: false,
                beforeSend: function(){},
                complete: function(){},
                error: function(){}
            }).error(function(){
                // Disable global error logging
                $.event.global.ajaxError = false;
            }).complete(function(){
                // Enable global error logging
                $.event.global.ajaxError = true;
            });

        }catch (e){}
    }
}

new ErrorHandling();

