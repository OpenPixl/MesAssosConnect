// importation des éléments necessaires à la page
import axios from 'axios';
import * as tailwind from '../../../../../../Francas2025/assets/js/composants/tailwind';
import {showNotification, showDialog, hideDialog} from "../../../../../../Francas2025/assets/js/composants/tailwind";
import {change_selectcity, zipcode, formatDate, formatTel} from "../../../../../../Francas2025/assets/js/composants/fonctions";

export function initShowAssociationPage() {
    console.log('Bonjour, vous êtes sur la page dédiée à une Association.')

    const dialog = document.getElementById("dialog")

    const btnAddMember = document.getElementById("btnAddMember")
    const searchForm = document.getElementById("SearchFormCustomer")
    const searchInput = document.getElementById('search_member_slug')
    const searchResults = document.getElementById('searchResults')

    btnAddMember.addEventListener("click", openDialog)
    searchInput.addEventListener("input", searchMember)

    function openDialog(e) {
        e.preventDefault();
        let url = e.currentTarget.href;
        let [crud,title,option] = e.currentTarget.dataset.array.split('-');
        dialog.querySelector('#modal_header_title').textContent = title;
        if(['ADDMEMBER', 'EDITMEMBER', 'SHOWMEMBER'].includes(crud)){
            axios
                .get(url)
                .then(function({data}) {
                    if(crud === 'SHOWMEMBER'){
                        dialog.querySelector('#modal_body_text').innerHTML = data.view;
                        dialog.querySelector('#modal_footer .validModal').classList.add('hidden');

                        const menu = dialog.querySelector('#modal_body_text ul');
                        let activeLink = menu.querySelector('.active');

                        if (activeLink) {
                            axios.get(activeLink.href).then(function({data}) {
                                dialog.querySelector('#modal_body_text article').innerHTML = data.formView;
                            })
                        } else {

                        }
                        // Ecoute tous les clics sur le menu pour actualiser le positionnement de la class 'active'
                        menu.addEventListener("click", function(event) {
                            if (event.target.tagName === "A") {
                                // Retirer "active" de tous les liens
                                menu.querySelectorAll("a").forEach(link => {
                                    e.preventDefault();
                                    link.classList.remove("active", "text-white", "bg-sky-700");
                                    link.classList.add("text-gray-500", "border", "border-sky-700");
                                });
                                // Ajouter "active" + styles sur le lien cliqué
                                event.target.classList.add("active", "text-white", "bg-sky-700");
                                event.target.classList.remove("text-gray-500", "border", "bg-white");
                            }
                        });

                        declareEvent();

                    }
                    else{
                        dialog.querySelector('#modal_body_text').innerHTML = data.formView;
                        dialog.querySelector('#modal_footer .validModal').href = url;
                        let commune = document.getElementById('member_city');
                        let cp = document.getElementById('member_zipcode');
                        let selectcity = document.getElementById('selectcity');
                        cp.addEventListener('input', function (event) {
                            zipcode(cp, commune, selectcity);
                        });
                        selectcity.addEventListener('change', function (event) {
                            change_selectcity(zipcode, commune, selectcity);
                        });
                        declareEvent();
                    }
                })
            showDialog();
        }
        else if(crud === 'ADDCOTISATION'){
            axios
                .get(url)
                .then(function({data}) {
                    dialog.querySelector('#modal_body_text').innerHTML = data.formView;
                    dialog.querySelector('#modal_footer .validModal').href = url;
                    const selectCotisation = document.querySelector('#adhesion_cotisation');
                    const priceCotisation = document.querySelector('#adhesion_priceCotisation');
                    const nameCotisation = document.querySelector('#adhesion_cotisationName');

                    // Mise à jour si la sélection de cotisation change
                    selectCotisation.addEventListener('change', function () {
                        updateInfos(this.value, priceCotisation, nameCotisation);
                    });
                    // adaptation au chargement initial de la modal d'adhésion.
                    if (selectCotisation.value) {
                        updateInfos(selectCotisation.value, priceCotisation, nameCotisation);
                    }
                    declareEvent();
                })
            showDialog();
        }
    }

    function openFormDialog(e){
        e.preventDefault();
        let article = dialog.querySelector('#modal_body_text article')
        axios
            .get(e.currentTarget.href)
            .then(function({data}) {
                article.innerHTML = data.formView
            })
            .catch(function(error){
                console.log(error)
            })
    }

    function submitModal(e){
        e.preventDefault();
        let form = e.currentTarget.parentNode.parentElement.querySelector('#modal_body form');
        if(form){
            let action = form.action;
            let data = new FormData(form);
            console.log(form.id);
            if (form.id === 'formMember'){
                axios
                    .post(action, data)
                    .then(function({data}) {
                        if(data.code === 422){
                            document.querySelector('#modal_body_text').innerHTML = data.formView;
                            showNotification('warning', data.message);
                            declareEvent();
                        }else{
                            document.querySelector('#liste').innerHTML = data.liste;
                            showNotification('success', data.message);
                            hideDialog();
                            declareEvent();
                        }
                    })
                    .catch()
            }
            else if(form.id === 'formAdhesion'){
                axios
                    .post(action, data)
                    .then(function({data}) {
                        if(data.code === 422){
                            document.querySelector('#modal_body_text').innerHTML = data.formView;
                            showNotification('warning', data.message);
                            declareEvent();
                        }else{
                            document.querySelector('#liste').innerHTML = data.liste;
                            showNotification('success', data.message);
                            hideDialog();
                            declareEvent();
                        }
                    })
                    .catch()
            }
        }
    }

    function searchMember(e){
        e.preventDefault();
        searchResults.classList.remove('hidden');
        const query = this.value.trim();
        if (query.length > 0) {
            let action = searchForm.action;
            let data = new FormData(searchForm);
            axios
                .post(action, data)
                .then(({data}) => {
                    searchResults.innerHTML = data.liste;
                    declareEvent();
                })
                .catch(function (error){
                    console.log(error);
                })
            ;
            declareEvent();
        }
    }

    function addMemberAsso(e){
        e.preventDefault();
        axios
            .post(e.currentTarget.href)
            .then(({data}) => {
                document.querySelector('#liste').innerHTML = data.liste;
                showNotification('success', data.message);
            })
            .catch()
    }

    function updateInfos(cotisationId, priceCotisation, nameCotisation) {
        if (!cotisationId) {
            priceCotisation.value = ''
            nameCotisation.value = ''
            return;
        }

        axios.get(`/gestion/adhesions/cotisation/${cotisationId}/infos`)
            .then(({data}) => {
                priceCotisation.value = data.price
                nameCotisation.value = data.name
            })
            .catch(err => {
                console.error("Erreur lors de la récupération du prix :", err);
            });
    }

    // Clic extérieur
    document.addEventListener('click', () => {
        searchResults.classList.add('hidden');
        searchForm.reset();
    });

    function hideModal(e) {
        e.preventDefault()
        hideDialog();
    }

    function declareEvent() {
        let btnClosedDialog = document.querySelectorAll('.modal_closed');
        let btnsSubmitModal = document.querySelectorAll('.validModal');
        let btnsOpenModal = document.querySelectorAll('.openModal');
        let memberResultsLinks = document.querySelectorAll('.result');
        let btnsOpenFormModal = document.querySelectorAll('.openFormModal');
        btnClosedDialog.forEach(el => {
            el.addEventListener('click', hideModal)
        })
        btnsOpenModal.forEach(el => {
            el.addEventListener('click', openDialog)
        })
        btnsSubmitModal.forEach(el => {
            el.addEventListener('click', submitModal)
        })
        memberResultsLinks.forEach(el => {
            el.addEventListener('click', addMemberAsso)
        })
        btnsOpenFormModal.forEach(el => {
            el.addEventListener('click', openFormDialog)
        })
    }

    declareEvent();
}