const modalAdhesion = document.getElementById("modalAdhesion");
const modalAdhesionBS = new bootstrap.Modal(modalAdhesion);
const btnShowAdhesion = document.querySelectorAll(".btnShowModalAdhesion");

const modalCotisation = document.getElementById("modalCotisation");
const modalCotisationBS = new bootstrap.Modal(modalCotisation);
const btnShowCotisation = document.querySelectorAll(".btnShowModalCotisation");

const btnSubmitForm = document.querySelectorAll('.submitForm');
// Tableau pour stocker les valeurs des checkboxes cochées
let selectedValues = [];
// Sélectionne toutes les checkboxes de la page
const tables = document.querySelectorAll('.table');
const navlink = document.querySelectorAll('.nav-link');

// Modifie l'état de toutes les chexkbox de la page à son chargement initial
const checkboxes = document.querySelectorAll('input[type="checkbox"]');
checkboxes.forEach(checkbox => {
    checkbox.checked = false; // Décocher chaque checkbox
});

function tableCheckboxValues(tables, selectedValues) {
    tables.forEach(table => {
        // Utiliser l'événement délégué pour écouter les clics sur toutes les lignes
        table.addEventListener('click', function (event) {
            // Vérifier si le clic a eu lieu sur une cellule <td> et pas sur la checkbox elle-même
            if (event.target.tagName === 'TD' && event.target.querySelector('input[type="checkbox"]') === null) {
                // Trouver la checkbox dans la ligne parente de la cellule cliquée
                const checkbox = event.target.parentElement.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked; // Inverser l'état de la checkbox
                }
            }
        });
    })
}

// Fonction pour analyser le tableau dans le panel actif
function updateSelectedCheckboxes() {
    // Réinitialiser le tableau des checkboxes sélectionnées
    selectedValues = [];

    // Trouver le panel actif
    const activePanel = document.querySelector('.tab-pane.active');
    if (activePanel) {
        // Trouver toutes les checkboxes cochées dans le tableau du panel actif
        const checkedCheckboxes = activePanel.querySelectorAll('.table tbody .checkbox:checked');

        // Ajouter les valeurs des checkboxes cochées dans selectedCheckboxes
        checkedCheckboxes.forEach(checkbox => {
            selectedValues.push(checkbox.value);
        });
    }

    // Afficher le tableau des checkboxes sélectionnées dans la console (ou dans la modale)
    console.log("Checkboxes sélectionnées : ", selectedValues);
}

function showAdhesion(event) {
    let opt = this.getAttribute('data-bs-whatever');
    let crud = opt.split('-')[0];
    let contentTitle = opt.split('-')[1];
    let id = opt.split('-')[2]
    //let url = this.href;
    document.getElementById('modalAdhesion').querySelector('.modal-title').textContent = contentTitle;
    if(crud === 'ADD'){
        modalAdhesionBS.show();
        modalAdhesion.querySelector('.modal-body').innerHTML = "<p>AJOUT</p>";
        axios
            .get('/gestion/adhesion/newOnAssociation/'+id)
            .then(function(response){
                modalAdhesion.querySelector('.modal-body').innerHTML = response.data.formView;
                tomSelect('#adhesion_on_asso_typeAdhesion');
                tomSelect('#adhesion_on_asso_members');
            })
            .catch()
    }else if(crud === 'CLONE'){
        updateSelectedCheckboxes();
        let id = (selectedValues[0]);
        if(selectedValues.length === 0 ){
            modalAdhesionBS.show();
            modalAdhesion.querySelector('.modal-body').innerHTML = "<p class=\"mb-0\">Attention, aucune lignes sélectionnées. Nous ne pouvons pas dupliquer l'adhésion.<br>Veuillez en sélectionner une. Merci</p>";
        }else if(selectedValues.length > 1 ){
            modalAdhesionBS.show();
            modalAdhesion.querySelector('.modal-body').innerHTML = "<p class=\"mb-0\">Attention, plusieurs lignes sont sélectionnées.<br>Veuillez en sélectionner une. Merci</p>";
        }else{}
    }else if(crud === 'EDIT'){
        updateSelectedCheckboxes();
        let id = (selectedValues[0]);
        if(selectedValues.length === 0 ){
            modalAdhesionBS.show();
            modalAdhesion.querySelector('.modal-body').innerHTML = "<p class=\"mb-0\">Attention, aucune lignes sélectionnées. Nous ne pouvons pas modifier l'adhésion.<br>Veuillez en sélectionner une. Merci</p>";
        }else if(selectedValues.length > 1 ){
            alert('Attention, plusieurs lignes sont sélectionnées.<br>Veuillez en sélectionner une. Merci')
        }else{
            modalAdhesionBS.show()
            modalAdhesion.querySelector('#submitAdhesion').setAttribute("data-target", 'formAdhesion');
            axios
                .get('/gestion/adhesion/'+id+'/editOnAsso')
                .then(function(response){
                    modalAdhesion.querySelector('.modal-body').innerHTML = response.data.formView;
                    tomSelect('#adhesion_on_asso_typeAdhesion');
                    tomSelect('#adhesion_on_asso_members');
                })
                .catch()
        }
    }else if(crud === 'DEL'){
        updateSelectedCheckboxes();
        if(selectedValues.length === 0 ){
            alert('Attention, aucune ligne sélectionnée.<br>Veuillez en sélectionner une. Merci')
        }else{
            modalAdhesionBS.show();
            modalAdhesion.querySelector('#submitAdhesion').setAttribute("data-target", 'delAdhesion');
            modalAdhesion.querySelector('.modal-body').innerHTML = "<p class=\"mb-0\">Vous êtes sur le point de supprimer au minimum une adhésion de votre association ?</p><p class=\"mb-0\">Etes vous sur de cette opération ?</p>";
        }
    }
}

function showCotisation(event) {
    let opt = this.getAttribute('data-bs-whatever');
    let crud = opt.split('-')[0];
    let contentTitle = opt.split('-')[1];
    //let url = this.href;
    document.getElementById('modalCotisation').querySelector('.modal-title').textContent = contentTitle;
    if(crud === 'ADD'){
        let id = opt.split('-')[2]
        modalCotisation.querySelector('#submitCotisation').setAttribute("data-target", 'formTypeAdhesion');
        axios
            .get('/gestion/typeadhesion/new/'+ id)
            .then(function(response){
                modalCotisation.querySelector('.modal-body').innerHTML = response.data.formView;
            })
            .catch()
    }else if(crud === 'EDIT'){
        updateSelectedCheckboxes();
        let id = (selectedValues[0]);
        if(selectedValues.length === 0 ){
            alert('Attention, aucune lignes sélectionnées.<br>Veuillez en sélectionner une. Merci')
        }else if(selectedValues.length > 1 ){
            alert('Attention, plusieurs lignes sont sélectionnées.<br>Veuillez en sélectionner une. Merci')
        }else{
            modalCotisationBS.show();
            modalCotisation.querySelector('#submitCotisation').setAttribute("data-target", 'formTypeAdhesion');
            modalCotisation.querySelector('.modal-body').innerHTML = "<p>MODIF</p>";
            axios
                .get('/gestion/typeadhesion/'+id+'/edit')
                .then(function(response){
                    modalCotisation.querySelector('.modal-body').innerHTML = response.data.formView;
                })
                .catch()
        }
    }else if(crud === 'DEL'){
        updateSelectedCheckboxes();
        if(selectedValues.length === 0 ){
            alert('Attention, aucune ligne sélectionnée.<br>Veuillez en sélectionner une. Merci')
        }else{
            modalCotisationBS.show();
            modalCotisation.querySelector('#submitCotisation').setAttribute("data-target", 'delCotisation');
            modalCotisation.querySelector('.modal-body').innerHTML = "<p class=\"mb-0\">Vous êtes sur le point de supprimer au minimum une cotisation de votre association ?</p><p class=\"mb-0\">Etes vous sur de cette opération ?</p>";
        }
    }

}

function submitForm(event){
    event.preventDefault();
    let target = this.getAttribute('data-target');
    if(target === "delCotisation"){
        for(let value of selectedValues ){
            axios
                .post("/gestion/typeadhesion/del/" + value)
                .then(function(response){
                    let activePanel = document.querySelector('.tab-pane.active');
                    if (activePanel) {
                        const rows = activePanel.querySelector('.table .rows');
                        rows.innerHTML = response.data.list;
                    }
                })
                .catch(function(error){
                    console.log(error);
                })
        }
        modalCotisationBS.hide();
        reloadSelectValues();
    }else if(target === "delAdhesion"){
        for(let value of selectedValues ){
            axios
                .post("/gestion/adhesion/del/" + value)
                .then(function(response){
                    let activePanel = document.querySelector('.tab-pane.active');
                    if (activePanel) {
                        const rows = activePanel.querySelector('.table .rows');
                        rows.innerHTML = response.data.list;
                    }
                })
                .catch(function(error){
                    console.log(error);
                })
        }
        modalAdhesionBS.hide();
        reloadSelectValues();
    }else{
        let form = document.getElementById(target);
        let action = form.action
        let data = new FormData(form);
        axios
            .post(action, data)
            .then(function(response){
                modalCotisationBS.hide();
                let activePanel = document.querySelector('.tab-pane.active');
                if (activePanel) {
                    const rows = activePanel.querySelector('.table .rows');
                    rows.innerHTML = response.data.list;
                }
                reloadSelectValues();
            })
            .catch()
    }
}

function tomSelect(input_id){
    new TomSelect(input_id, {
        plugins: ['remove_button'],
        create: true,
        onItemAdd:function(){
            this.setTextboxValue('');
            this.refreshOptions();
        },
        render:{
            option:function(data,escape){
                return '<div class="d-flex"><span>' + escape(data.data) + '</span><span class="ms-auto text-muted">' + escape(data.value) + '</span></div>';
            },
            item:function(data,escape){
                return '<div>' + escape(data.data) + '</div>';
            }
        }
    });
}

function reloadSelectValues(){
    let activePanel = document.querySelector('.tab-pane.active');
    if (activePanel) {
        selectedValues = [];
        // Réinitialiser les checkboxes dans le DOM (facultatif)
        const table = activePanel.querySelector('table');
        const checkboxes = table.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        console.log(selectedValues);
    }

}

function loadEvents() {
    tableCheckboxValues(tables, selectedValues);
    document.querySelectorAll(".btnShowModalAdhesion").forEach(function(link){link.addEventListener('click', showAdhesion);});
    document.querySelectorAll(".btnShowModalCotisation").forEach(function(link){link.addEventListener('click', showCotisation);});
    //navlink.forEach(function(link){link.addEventListener('click', emptySelectedValues);})
    btnSubmitForm.forEach(function(link){link.addEventListener('click', submitForm);})
}

loadEvents()

modalAdhesion.addEventListener('hidden.bs.modal', function(){
    modalAdhesion.querySelector('.modal-title').textContent = "Adhésions";
    modalAdhesion.querySelector('.modal-body').innerHTML =
        "<div class=\"d-flex justify-content-center\">"+
        "<div class=\"spinner-border text-primary\" role=\"status\">"+
        "<span class=\"visually-hidden\">Loading...</span>"+
        "</div>"+
        "</div>";
})

modalCotisation.addEventListener('hidden.bs.modal', function(){
    modalAdhesion.querySelector('.modal-title').textContent = "Cotisations";
    modalAdhesion.querySelector('.modal-body').innerHTML =
        "<div class=\"d-flex justify-content-center\">"+
        "<div class=\"spinner-border text-primary\" role=\"status\">"+
        "<span class=\"visually-hidden\">Chargement ...</span>"+
        "</div>"+
        "</div>";
})