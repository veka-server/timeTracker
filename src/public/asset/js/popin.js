/**
 * Classe de Popin
 */

class Popin {

    static template;

    constructor() {
        this.html = '';
        this.id = this.guid();
        this.init();
    }

    init = function(){
        this.html = $('<div/>', { html: Popin.template });
        this.html.find('.background_popup_main').attr('id',this.id);

        const current_instance = this;
        current_instance.setTitle('');
        current_instance.setContent('');
        return this;
    }

    setTitle = function(titre){
        this.html.find('.background_popup_main .title .title_text').html(titre);
        return this;
    }

    setContent = function(content){
        this.html.find('.background_popup_main .content').html(content);
        return this;
    }

    close = function(){
        const current_instance = this;

        /** si presence du flag alors on ne ferme pas la popin */
        if($('#'+this.id+' .popin-container').attr('data-no-close') !== undefined){
            $('#'+this.id+' .popin-container').removeAttr('data-no-close');
            return ;
        }

        $('#'+this.id+' .popin-container').addClass('popout');

        setTimeout(function(){
            $('#'+current_instance.id).remove();
        },250)
    }

    abonnements = function(){
        const current_instance = this;

        /** capture du clic sur la croix  */
        this.html.find('#'+this.id+' .close_popin').off().click(function(){
            let event = $.Event( "popin_close" );
            $( '#'+current_instance.id ).trigger( event );
            current_instance.close();
        });

        /** capture du clic sur le bouton cancel */
        this.html.find('#'+this.id+' .footer .cancel').off().click(function(){
            let event = $.Event( "popin_cancel" );
            $( '#'+current_instance.id ).trigger( event );
            current_instance.close();
        });

        /** capture du clic sur le bouton valid */
        this.html.find('#'+this.id+' .footer .valid').off().click(function(){
            let event = $.Event( "popin_valid" );
            $( '#'+current_instance.id ).trigger( event );
            current_instance.close();
        });
    }

    onClose = function(callback){
        this.html.find('#'+this.id).on('popin_close', function(e) {
            if(callback && typeof callback === "function") {
                callback(e);
            }
        });
        return this;
    }

    onCancel = function(callback){
        this.html.find('#'+this.id).on('popin_cancel', function(e) {
            if(callback && typeof callback === "function") {
                callback(e);
            }
        });
        return this;
    }

    onValid = function(callback){
        this.html.find('#'+this.id).on('popin_valid', function(e) {
            if(callback && typeof callback === "function") {
                callback(e);
            }
        });
        return this;
    }

    /** uniq id */
    guid = function(){
        let s4 = () => {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    }

    show = function(){
        const current_instance = this;
        current_instance.abonnements();
        $('body').append(this.html)
        $('#'+this.id).show();
        return this;
    }

    hideCancelButton = function(){
        this.html.find('.background_popup_main .footer .cancel').remove();
        return this;
    }

    hideValidButton = function(){
        this.html.find('.background_popup_main .footer .valid').remove();
        return this;
    }

    static alert = function(msg, titre, call_valid){
        new Popin()
            .setTitle(titre)
            .setContent(msg)
            .hideCancelButton()
            .onValid(call_valid)
            .show();
    }

    static confirm = function(msg, titre, call_valid, call_cancel){
        new Popin()
            .setTitle(titre)
            .setContent(msg)
            .onValid(call_valid)
            .onClose(call_cancel)
            .onCancel(call_cancel)
            .show();
    }

    static dialog = function(msg, titre, call_valid, call_cancel, call_close){
        new Popin()
            .setTitle(titre)
            .setContent(msg)
            .onValid(call_valid)
            .onClose(call_cancel)
            .onCancel(call_close)
            .show();
    }

}
