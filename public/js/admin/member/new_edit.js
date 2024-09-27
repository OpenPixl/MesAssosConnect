// Appel des constantes
const roleMenber = document.getElementById("member_roleMember");
const btnSubmitForm = document.querySelectorAll('.submitForm');

// Functions
const tsdiagChoice = new TomSelect("#member_roleMember", {
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