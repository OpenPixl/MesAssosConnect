// importation des éléments necessaires à la page
import axios from 'axios';
import * as tailwind from '../../../composants/tailwind';
import {showNotification, showDialog, hideDialog} from "../../../composants/tailwind";
import {change_selectcity, zipcode, formatDate, formatTel} from "../../../composants/fonctions";

export function initShowAssociationPage() {
    console.log('Bonjour, vous êtes sur la page dédiée à une Association.');

    const dialog = document.getElementById("dialog");

    const btnAddMember = document.getElementById("btnAddMember");
    const searchForm = document.getElementById("SearchFormCustomer");
    const searchInput = document.getElementById('search_member_slug');
    const searchResults = document.getElementById('searchResults');

    btnAddMember.addEventListener("click", openDialog);
    searchInput.addEventListener("input", searchMember);

    function openDialog(e) {
        e.preventDefault();
        let url = e.currentTarget.href;
        let [crud,title,option] = e.currentTarget.dataset.array.split('-');
        dialog.querySelector('#modal_header_title').textContent = title;
        if(crud === 'ADDMEMBER'){
            axios
                .get(url)
                .then(function({data}) {
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
                    const priceField = document.querySelector('#adhesion_priceCotisation');

                    // 🔹 1. Mise à jour si sélection change
                    selectCotisation.addEventListener('change', function () {
                        updatePrice(this.value, priceField);
                    });

                    // 🔹 2. Mise à jour au chargement initial
                    if (selectCotisation.value) {
                        updatePrice(selectCotisation.value, priceField);
                    }

                    declareEvent();
                })
            showDialog();
        }
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

    function updatePrice(cotisationId, priceField) {
        if (!cotisationId) {
            priceField.value = '';
            return;
        }

        axios.get(`/gestion/adhesions/cotisation/${cotisationId}/price`)
            .then(({data}) => {
                priceField.value = data.price;
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
    }

    declareEvent();
}