// importation des éléments necessaires à la page
import axios from 'axios';
import {showNotification, showDialog, hideDialog} from "../../../../../../Francas2025/assets/js/composants/tailwind";
import {change_selectcity, zipcode, formatDate, formatTel} from "../../../../../../Francas2025/assets/js/composants/fonctions";

export function initEditNewAssociationPage() {
    console.log('Bonjour, vous êtes sur la page d\'ajout ou de modification d\'une Association');

    // Soumission du formulaire principal par le bouton "cu-btnutils"
    const btnSubmitForm = document.getElementById('cu-btnutils');
    const btnModalCampaign = document.getElementById('btnAddCampaign');
    const dialog = document.getElementById("dialog");

    const form = document.querySelector('#formAssociation');

    btnSubmitForm.addEventListener('click', submitForm);
    btnModalCampaign.addEventListener('click', openDialog);

    function openDialog(e){
        e.preventDefault();
        let url = e.currentTarget.href;
        let [crud,title,option] = e.currentTarget.dataset.array.split('-');
        dialog.querySelector('#modal_header_title').textContent = title;
        console.log(crud, title);
        if(crud === 'ADDCAMPAIGN'){
            axios
                .get(url)
                .then(function({data}) {
                    dialog.querySelector('#modal_body_text').innerHTML = data.formView;
                    dialog.querySelector('#modal_footer .validModal').href = url;
                    declareEvent();
                })
                .catch(function(error) {
                    if (error.response && error.response.status === 409) {
                        dialog.querySelector('#modal_body_text').innerHTML = error.response.data.message;
                        declareEvent();
                    } else {
                        console.error(error);
                    }
                });
            showDialog();
        }
    }

    function submitModal(e){
        e.preventDefault();
        let form = e.currentTarget.parentNode.parentElement.querySelector('#modal_body form');
        if(form){
            let action = form.action;
            let data = new FormData(form);
            if (form.id === 'formCampaignAdhesion'){
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

    function submitForm(e){
        e.preventDefault();
        let action = form.action;
        let data = new FormData(form);
        axios
            .post(action, data)
            .then(function({data}) {
                document.getElementById('main_content').innerHTML = data.formView;
                showNotification();

            })
            .catch(function (error) {
                console.log(error);
            })
    }

    function hideModal(e) {
        e.preventDefault()
        hideDialog();
    }

    function declareEvent(){
        // fermeture de la fenetre modale
        let btnClosedDialog = document.querySelectorAll('.modal_closed');
        btnClosedDialog.forEach(el => {
            el.addEventListener('click', hideModal)
        })
        // Validation de la fenetre modale
        let btnsSubmitModal = document.querySelectorAll('.validModal');
        btnsSubmitModal.forEach(el => {
            el.addEventListener('click', submitModal)
        })

        let customer_commune = document.getElementById('association_city');
        let customer_zipcode = document.getElementById('association_zipcode');
        let customer_selectcity = document.getElementById('selectcity');
        customer_zipcode.addEventListener('input', function (event) {
            zipcode(customer_zipcode, customer_commune, customer_selectcity);
        });
        customer_selectcity.addEventListener('change', function (event) {
            change_selectcity(customer_zipcode, customer_commune, customer_selectcity);
        });
    }

    declareEvent();
}