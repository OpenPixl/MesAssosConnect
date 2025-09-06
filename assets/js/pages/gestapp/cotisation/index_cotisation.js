// importation des éléments necessaires à la page
import axios from 'axios';
import * as tailwind from '../../../composants/tailwind';
import {showNotification, showDialog, hideDialog} from "../../../composants/tailwind";
import {change_selectcity, zipcode, formatDate, formatTel} from "../../../composants/fonctions";

export function initIndexCotisationPage() {
    console.log('Bonjour, vous êtes sur la page dédiée à la gestion des cotisations.');

    const btnAddCotisation = document.getElementById("btnAddCotisation");
    console.log(btnAddCotisation);

    let dialog = document.getElementById("dialog");

    btnAddCotisation.addEventListener("click", openDialog)

    function openDialog(e) {
        e.preventDefault();
        let url = e.currentTarget.href;
        let [crud,title,option] = e.currentTarget.dataset.array.split('-');
        dialog.querySelector('#modal_header_title').textContent = title;
        if(crud === 'ADDCOTISATION'){
            axios
                .get(url)
                .then(function({data}) {
                    dialog.querySelector('#modal_body_text').innerHTML = data.formView;
                    dialog.querySelector('#modal_footer .validModal').href = url;

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
            if (form.id === 'formCotisation'){
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
            else{
                showNotification('warning', '<p>Erreur JS de soumission. <br>Le formulaire n\'est pas le bon</p>');
            }
        }
    }


    function hideModal(e) {
        e.preventDefault()
        hideDialog();
    }

    function declareEvent() {
        let btnClosedDialog = document.querySelectorAll('.modal_closed');
        let btnsSubmitModal = document.querySelectorAll('.validModal');
        let btnsOpenModal = document.querySelectorAll('.openModal');

        btnClosedDialog.forEach(el => {
            el.addEventListener('click', hideModal)
        })
        btnsOpenModal.forEach(el => {
            el.addEventListener('click', openDialog)
        })
        btnsSubmitModal.forEach(el => {
            el.addEventListener('click', submitModal)
        })
    }

    declareEvent();
}