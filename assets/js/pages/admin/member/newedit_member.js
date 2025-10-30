// importation des éléments necessaires à la page
import axios from 'axios';
import * as tailwind from '../../../../../../Francas2025/assets/js/composants/tailwind';
import {showNotification, showDialog, hideDialog} from "../../../../../../Francas2025/assets/js/composants/tailwind";
import {change_selectcity, zipcode} from "../../../../../../Francas2025/assets/js/composants/fonctions";

export function initNewEditMemberPage() {
    console.log('Bonjour, vous êtes sur la page dédiée à la gestion des membres.');

    const formMember = document.getElementById('formMember');
    const btnSubmitMember = document.getElementById('btnSubmitMember');
    let commune = document.getElementById('member_city');
    let cp = document.getElementById('member_zipcode');
    let selectcity = document.getElementById('selectcity');

    console.log(commune, cp, selectcity)

    function submitFormMember(e){
        e.preventDefault();
        let action = formMember.action;
        let data = new FormData(formMember);
        axios
            .post(action, data)
            .then(function({data}) {
                if(data.code === 422){
                    formMember.outerHTML = data.formView;
                    showNotification('warning', data.message);
                    declareEvent();
                }else{
                    showNotification('success', data.message);
                    hideDialog();
                    declareEvent();
                    btnSubmitMember.classList.add('disabled:opacity-50');                             // supprime la classe disabled:opacity-50
                    btnSubmitMember.setAttribute('disabled', 'disabled')                                     // retire l'attribut disabled
                }
            })
    }

    function declareEvent(){
        formMember.addEventListener('input', () => {
            btnSubmitMember.classList.remove('disabled:opacity-50');                             // supprime la classe disabled:opacity-50
            btnSubmitMember.removeAttribute('disabled')                                     // retire l'attribut disabled
        });
        cp.addEventListener('input', function (event) {
            zipcode(cp, commune, selectcity);
        });
        selectcity.addEventListener('change', function (event) {
            change_selectcity(zipcode, commune, selectcity);
        });

        btnSubmitMember.addEventListener('click', submitFormMember)
    }

    declareEvent();
}