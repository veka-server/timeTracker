
    class Pagination {

        constructor(table) {
            this.table = table;

            this.xhr;
            this.xhr_export;

            this.order_by = null;

            this.pagination = $(this.table).closest('.container_table').find('.pagination');

            this.envoi_first = $(this.pagination).find('.envoi_first');
            this.envoi_previous = $(this.pagination).find('.envoi_previous');
            this.envoi_next = $(this.pagination).find('.envoi_next');
            this.envoi_last = $(this.pagination).find('.envoi_last');
            this.page_curr = $(this.pagination).find('.page_curr');
            this.page_nb = $(this.pagination).find('.page_nb');
            this.page_size = $(this.pagination).find('.page_size');
            this.request_time = $(this.pagination).find('.request_time');
            this.export_btn_with_pagination = $(this.table).closest('.container_table').find('.btn_export');
            this.field_order_by = $(this.table).find('.field_order_by');

            this.old_filtre = '';

            const current_instance = this;

            if($(this.table).find('.filtre_extend').length > 0){
                $(this.table).closest('.container_table').find('.show_filter').show();
            }

            $(this.page_curr).off().change(function(){
                const current_val = $(current_instance.page_curr).val();
                current_instance.updateCurrentNbPage(current_val);
                current_instance.getTableau();
            });

            $(this.envoi_first).off().click(function(){
                current_instance.updateCurrentNbPage(1);
            });

            $(this.envoi_previous).off().click(function(){
                const current_val = parseInt($(current_instance.page_curr).val()) - 1;
                current_instance.updateCurrentNbPage(current_val);
            });

            $(this.envoi_next).off().click(function(){
                const current_val = parseInt($(current_instance.page_curr).val()) + 1;
                current_instance.updateCurrentNbPage(current_val);
            });

            $(this.envoi_last).off().click(function(){
                current_instance.getTableau('last');
            });

            $('[data-input-for-table-id="'+$(this.table).find('table').attr('data-id-unique')+'"]').change(function() {
                current_instance.getTableau();
            });

            /** capture du clic dans la selection du nombre d'element par page */
            $(this.page_size).off().change(function(){
                current_instance.updateCurrentNbPage(1);
                current_instance.getTableau();
            });

            /** gestion de l'auto search dans les input du  header */
            let timer;
            $(this.table).find('.filtre_colonne').off().keyup(function(){
                clearTimeout(timer);
                timer = setTimeout(function() {
                    current_instance.updateCurrentNbPage(1);
                    current_instance.getTableau();
                }, 500);
            });

            $(this.table).on('Tableau::pagination_refresh', function(){
                current_instance.getTableau();
            });

            $(this.table).on('Tableau::show_error_inside_tableau', function(event){
                $(current_instance.table).closest('.table_wrapper').addClass('hide_scroll');
                $(current_instance.table).closest('.table_wrapper').scrollTop(0);
                $(current_instance.table).closest('.table_wrapper').scrollLeft(0);
                $(current_instance.table).closest('.table_wrapper').find('.error_msg').show();
                $(current_instance.table).closest('.table_wrapper').find('.msg_error').html(event.msg);
                $(current_instance.table).closest('.table_wrapper').find('.refresh_tableau').off().click(function(){
                    current_instance.getTableau();
                });
            });

            $(this.table).on('Tableau::pagination_call_me_with_filter', function(event, event_name_response){
                const data = current_instance.getFiltreForPost(); // filtre envoyé sous forme de post
                data['filtre'] = current_instance.getFiltre(); // filtre envoyé dans le $_POST['filtre'], cela correspond aux filtres du header
                $('body').trigger(event_name_response, [data]);
            });

            $(this.table).on('Tableau::pagination_add_loading', function(){
                $(current_instance.table).closest('.table_wrapper').scrollTop(0);
                $(current_instance.table).closest('.table_wrapper').scrollLeft(0);
                $(current_instance.table).closest('.table_wrapper').addClass('loading');
            });

            $(this.table).on('Tableau::pagination_remove_loading', function(){
                $(current_instance.table).closest('.table_wrapper').removeClass('loading');
            });

            $(this.table).on('Tableau::complete', function(){

                $('.tableau_action_button').off().click(function(){

                    let url = $(this).attr('data-url');
                    let confirmation_msg = $(this).attr('data-confirmation_msg');
                    let type_popin_retour = $(this).attr('data-type_popin_retour');
                    if(type_popin_retour === undefined || type_popin_retour.length <= 0){
                        type_popin_retour = 'alert';
                    }

                    let data = [];
                    try{
                        data = JSON.parse($(this).attr('data-to_send'));
                    }catch (e){}

                    $(current_instance.table).closest('.table_wrapper').scrollTop(0);
                    $(current_instance.table).closest('.table_wrapper').scrollLeft(0);
                    $(current_instance.table).closest('.table_wrapper').addClass('loading');

                    let valid_callback = function(response){

                        let success = $(response).getSuccessMsgFromResponse();
                        Popin[type_popin_retour](success.content, success.titre)

                        $(current_instance.table).closest('.table_wrapper').removeClass('loading');

                        if(type_popin_retour === 'alert'){
                            current_instance.getTableau();
                        }

                        $(current_instance.table).attr('data-refreshme', true);
                    }

                    if(confirmation_msg !== undefined && confirmation_msg !== '' && confirmation_msg.length > 0){

                        Popin.confirm(confirmation_msg, Trad.generic_confirm_title_popin, function(){
                            current_instance.xhr = $(current_instance.table).postForTableau( url, data, valid_callback);
                        }, function(){
                            $(current_instance.table).closest('.table_wrapper').removeClass('loading');
                        });

                    } else {
                        current_instance.xhr = $(current_instance.table).postForTableau( url, data, valid_callback);
                    }

                });

            });

            // Button export
            this.export_btn_with_pagination.click(function(){

                if($(this).attr('disabled') !== undefined){
                    return ;
                }

                let url = 'export';
                if($(this).attr('data-url') !== undefined){
                    url = $(this).attr('data-url');
                }

                current_instance.export(url);
            });

            // gestion des order by
            $(current_instance.field_order_by).each(function(){

                $(this).after().click(function(){

                    const elem = $(this);

                    const remove_list = $(current_instance.table).find('.field_order_by:not([data-sort="' + $(elem).attr('data-sort') + '"])');
                    remove_list.removeClass('down');
                    remove_list.removeClass('up');

                    if($(this).hasClass('down')){
                        $(this).removeClass('down');
                        current_instance.order_by = null;
                    } else if($(this).hasClass('up')){
                        $(this).removeClass('up');
                        $(this).addClass('down');
                        current_instance.order_by = $(this).attr('data-sort')+'-desc';
                    } else {
                        $(this).addClass('up');
                        current_instance.order_by = $(this).attr('data-sort')+'-asc';
                    }

                    current_instance.getTableau();
                });

            });

            /** gestion des order by up actif au chargement de la page */
            $(this.table).find('[data-default-sort="up"]').each(function(){
                $(this).addClass('up');
                this.order_by = $(this).attr('data-sort')+'-asc';
            });

            /** gestion des order by down actif au chargement de la page */
            $(this.table).find('[data-default-sort="down"]').each(function(){
                $(this).addClass('down');
                this.order_by = $(this).attr('data-sort')+'-desc';
            });

            /** gestion du clic sur le bouton des filtres */
            $(this.table).closest('.container_table').find('.show_filter').click(function(){
                $(current_instance.table).find('.filtre_extend').slideToggle();
                $(this).toggleClass('active');
            });

            this.getTableau();

        }

        /** met a jour l'input de la page en cours et appel l'ajax de changement de page */
        updateCurrentNbPage = function(current_val){

            current_val = parseInt(current_val);

            const original_val = $(this.page_curr).val();
            const max_page = $(this.page_curr).attr('max');

            if(current_val <= 0 || isNaN(current_val)){
                current_val = 1;
            }

            if(max_page != 0 && current_val > max_page){
                current_val = max_page;
            }

            if(original_val == current_val){
                return ;
            }

            $(this.page_curr).val(current_val);
            $(this.page_curr).change();
        };

        /**
         * appeler apres chaque chargement de donnée
         * gestion de l'affichage de la pagination
         * show / hide bouton
         * show / hide nombre max de page
         */
        update(response){

            /** resize auto de la colonne action */
            $(this.table).find('td.action_column').css('display','inline-block');
            let width_content_column_action = $(this.table).find('td.action_column').outerWidth();
            $(this.table).find('td.action_column').css('display','table-cell');
            $(this.table).find('.action_column').css('width',width_content_column_action+'px');

            /*
            if(response.no_nb_page === undefined || response.no_nb_page != true){
                $(this.page_curr).attr('max',response.page_nb)
                $(this.page_nb).html(' / '+response.page_nb);
            } else {
                $(this.page_curr).attr('max',0)
            }
             */

            if($(this.page_curr).val() == 1) {
                $(this.envoi_first).hide();
                $(this.envoi_previous).hide();
            } else {
                $(this.envoi_first).show();
                $(this.envoi_previous).show();
            }

            if(response.last == true){
                $(this.envoi_next).hide();
                $(this.envoi_last).hide();
            } else {
                $(this.envoi_next).show();
                $(this.envoi_last).show();
            }

        };

        /** retourne les filtres + order by pour les requetes ajax
         * reset le nombre max de page si changement de filtre
         * cette fonction retourne les filtre present dans les header du tablaeu */
        getFiltre = function(){

            // ajout des filtre inclu dans le header du tableau
            const filtre = {};
            $(this.table).find('.filtre_colonne').each(function() {
                filtre[$(this).attr('name')] = $(this).val();
            });

            // reset le nombre de page si l'on change un filtre
            const filtre_test = $.map(filtre, function (e) {
                return e;
            }).join(',,, ');
            if(filtre_test !== this.old_filtre){
                $(this.page_nb).html('');
            }
            this.old_filtre = filtre_test;

            // ajout du order by dans les filtres
            if(this.order_by != null){
                filtre['order_by'] = this.order_by;
            }

            return filtre;
        }

        /** retourne les filtre a ajouter sous forme de post
         * reset le nombre max de page si changement de filtre
         * cette fonction retourne les filtre present hors du tableau */
        getFiltreForPost = function(){

            // ajout des filtre
            const filtre = {};

            $('[data-input-for-table-id="'+$(this.table).find('table').attr('data-id-unique')+'"]').each(function() {
                filtre[$(this).attr('name')] = $(this).val();
            });

            // reset le nombre de page si l'on change un filtre
            const filtre_test = $.map(filtre, function (e) {
                return e;
            }).join(',,, ');
            if(filtre_test !== this.old_filtre){
                $(this.page_nb).html('');
            }

            return filtre;
        }

        /** retourne le nombre de colonnes du tableau */
        getMaxColCount(){
            var maxCol = 0;

            $(this.table).find('tr').each(function(i,o) {
                var colCount = 0;
                $(o).find('td:not(.maxcols),th:not(.maxcols)').each(function(i,oo) {
                    let cc = Number($(oo).attr('colspan'));
                    if (cc) {
                        colCount += cc;
                    } else {
                        colCount += 1;
                    }
                });
                if(colCount > maxCol) {
                    maxCol = colCount;
                }
            });

            return maxCol;
        }

        /** ajax + update content tableau */
        getTableau(force_page_curr){

            // remove error message si present
            $(this.table).find('.error_msg').closest('tr').remove();
            $(this.table).closest('.table_wrapper').find('.error_msg').hide();
            $(this.table).closest('.table_wrapper').removeClass('hide_scroll');

            if(this.xhr){
                this.xhr.abort();
            }

            $(this.table).closest('.table_wrapper').scrollTop(0);
            $(this.table).closest('.table_wrapper').scrollLeft(0);
            $(this.table).closest('.table_wrapper').addClass('loading');

            const data = this.getFiltreForPost(); // filtre envoyé sous forme de post
            data['page_curr']  = (force_page_curr!== undefined) ? force_page_curr : $(this.page_curr).val();
            data['page_size'] = $(this.page_size).val();
            data['filtre']    = this.getFiltre(); // filtre envoyé dans le $_POST['filtre'], cela correspond aux filtres du header

            let url = 'liste';
            if($(this.table).find('tbody').attr('data-liste-url') !== undefined){
                url = $(this.table).find('tbody').attr('data-liste-url');
            }

            const current_instance = this;

            this.xhr = $(this.table).postForTableau( url, data, function( response ) {

                $(current_instance.table).find('tbody').html(response.html);

                // afficher le nombre de page total si on atteint la dernière page
                if(response.last == true){
                    response.no_nb_page = undefined;
                }

                $(current_instance.page_curr).val(response.page_curr)
                current_instance.update(response);
                $(current_instance.table).closest('.table_wrapper').removeClass('loading');

            });

        }

        export(url){

            if(this.xhr_export){
                this.xhr_export.abort();
            }

            const data = this.getFiltreForPost(); // filtre envoyé sous forme de post
            data['filtre']    = this.getFiltre(); // filtre envoyé dans le $_POST['filtre'], cela correspond aux filtres du header
            data['export']    = true;

            try {
                this.xhr_export = $(this.export_btn_with_pagination).postForExport( url, data);
            }catch(e){
                console.error(e)
            }
        }

    }
