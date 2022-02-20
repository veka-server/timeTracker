/**
 * Classe de Validation
 */

class Validation {

    constructor() {

        this.flag_already_parsed = 'data-validation-already-parsed';
        this.flag_list_contrainte = 'data-validation-list-contrainte';
        this.flag_error = 'data-validation-error';

        this.parseAllHTML();
    }

    parseAllHTML = function() {
        const current_validation = this;
        $(':input['+this.flag_list_contrainte+']:not(['+this.flag_already_parsed+'])').each(function(){
            current_validation.parseOneInput(this);
        })
    }

    parseOneInput = function(input) {
        const current_validation = this;
        $(input).attr(this.flag_already_parsed, 'true');
        current_validation.abonnement(input);
    }

    abonnement = function(input) {
        const current_validation = this;
        $(input).on('change', function(){
            current_validation.checkAll(input);
        });
        $(input).change();
    }

    checkAll = function(input) {
        const current_validation = this;
        let liste_contrainte = $(input).attr(this.flag_list_contrainte);

        if(liste_contrainte === undefined || liste_contrainte.length === 0){
            return;
        }

        let error = {status:false, msg:''};
        $(liste_contrainte.split(',')).each(function(elem, contrainte){

            if(error.status === true){
                return ;
            }

            error = current_validation.check(input, contrainte);

            if(error === undefined){
                error = {status:true, msg:'la methode de la containte "'+contrainte+'" return undefined'};
            }

        });

        current_validation.showErrorStatut(input, error);
    }

    showErrorStatut(input, error) {
        if( error.status === undefined || error.status === true ){
            let error_msg = error.msg;
            $(input).closest('.global_input').attr(this.flag_error, error_msg);
            $(input).closest('.global_input').attr('title', error_msg);
        } else {
            $(input).closest('.global_input').removeAttr(this.flag_error);
            $(input).closest('.global_input').removeAttr('title');
        }
    }

    check = function(input, contrainte) {
        const current_validation = this;

        contrainte = contrainte.trim().toLowerCase();
        if(contrainte !== 'required' && current_validation.is_empty(input)){
            return {status:false, msg:''}
        }

        switch(contrainte){

            case 'numeric':
                return {
                    status: current_validation.is_numeric(input) === false
                    , msg: 'Ce champ n\'est pas un numeric'
                };

            case 'alphanumeric':
                return {
                    status: current_validation.is_alphanumeric(input) === false
                    , msg: 'Ce champ n\'est pas un alphanumeric'
                };

            case 'email':
                return {
                    status: current_validation.is_email(input) === false
                    , msg: 'Ce champ n\'est pas un email valide'
                };

            case 'required':
                return {
                    status: current_validation.is_empty(input) === true
                    , msg: 'Ce champ ne doit pas être vide'
                };

        }

    }

    is_numeric = function(input){
        let val = $(input).val();
        return !isNaN(parseFloat(val)) && isFinite(val);
    }

    is_alphanumeric = function(input){
        let val = $(input).val();
        let mail_format=/^[a-z0-9]+$/i;
        return val.match(mail_format);
    }

    is_email = function(input){
        let val = $(input).val();
        return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val));
    }

    is_empty = function(input){
        let val = $(input).val();
        return ( val === undefined || val.length === 0 );
    }

}
