// Appel des constantes
const btnSubmitForm = document.querySelectorAll('.submitForm');

// Functions

function submitForm(event){
    event.preventDefault();
    let target = this.getAttribute('data-target');
    let form = document.getElementById(target);
    let action = form.action
    let data = new FormData(form);
    axios
        .post(action, data)
        .then(function(response){

            reloadEvents;
        })
        .catch()
}

function reloadEvents(){
    btnSubmitForm.forEach(function(link){
        link.addEventListener('click', submitForm);
    })
}

// Liste des events
btnSubmitForm.forEach(function(link){
    link.addEventListener('click', submitForm);
})