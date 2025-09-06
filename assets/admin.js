// import './bootstrap.js';

import './styles/admin.css';
import './js/composants/inputs';
import { initDropdowns } from './js/composants/tailwind.js';
import { initEditNewAssociationPage } from './js/pages/gestapp/association/newedit_association';
import { initShowAssociationPage } from './js/pages/gestapp/association/show_association';
import { initNewEditMemberPage } from './js/pages/admin/member/newedit_member';
import { initIndexCotisationPage } from "./js/pages/gestapp/cotisation/index_cotisation";

// dropdown sur navbar
const dropdownButton = document.getElementById("menu-button");
const dropdownMenu = document.getElementById("menu-dropdown");
const mobileButton = document.getElementById('mobile-menu')
const mobileDropdown = document.getElementById('mobile-dropdown')

if(mobileButton !== null){
    mobileButton.addEventListener("click", function (event) {
        event.stopPropagation();
        mobileDropdown.classList.toggle("hidden");
        mobileButton.setAttribute("aria-expanded", mobileDropdown.classList.contains("hidden") ? "false" : "true");
    });
    document.addEventListener("click", function (event) {
        if (!mobileDropdown.contains(event.target)) {
            mobileDropdown.classList.add("hidden");
            mobileButton.setAttribute("aria-expanded", "false");
        }
    });
}

if(dropdownButton !== null){
    dropdownButton.addEventListener("click", function (event) {
        event.stopPropagation();
        dropdownMenu.classList.toggle("hidden");
        dropdownButton.setAttribute("aria-expanded", dropdownMenu.classList.contains("hidden") ? "false" : "true");
    });
    document.addEventListener("click", function (event) {
        if (!dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add("hidden");
            dropdownButton.setAttribute("aria-expanded", "false");
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initDropdowns();
    const page = document.body.dataset.page;
    switch (page) {
        case 'mac_admin_association_new':
        case 'mac_admin_association_edit':
            initEditNewAssociationPage();
            break;
        case 'mac_admin_association_show':
            initShowAssociationPage();
            break;
        case 'mac_admin_member_edit':
        case 'mac_admin_member_new':
            initNewEditMemberPage();
            break;
        case 'mac_gestion_cotisation_index':
        case 'mac_gestion_cotisation_indexbyAsso':
            initIndexCotisationPage();
            break;
        default:
            console.log('Page non reconnue ou pas de JS spécifique');
    }

});