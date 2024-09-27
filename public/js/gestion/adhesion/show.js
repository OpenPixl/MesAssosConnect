const modalAdhesion = document.getElementById("modalAdhesion");
const modalAdhesionBS = new bootstrap.Modal(modalAdhesion);
const btnShowAdhesion = document.querySelectorAll(".btnShowModalAdhesion");

// Fonctions
function showReco(event) {
    let opt = this.getAttribute('data-bs-whatever');
    let crud = opt.split('-')[0];
    let contentTitle = opt.split('-')[1];
    let id = opt.split('-')[2]
    //let url = this.href;
    modalAdhesionBS.show();
    document.getElementById('modalAdhesion').querySelector('.modal-title').textContent = contentTitle;
    if(crud === 'ADD'){
        modalAdhesion.querySelector('.modal-body').innerHTML = "<p>AJOUT</p>";
        axios
            .get('/gestion/adhesion/newModal')
            .then(function(response){
                modalAdhesion.querySelector('.modal-body').innerHTML = response.data.formView;
                reloadEvent;
            })
            .catch()
    }else if(crud === 'EDIT'){
        modalAdhesion.querySelector('.modal-body').innerHTML = "<p>MODIF</p>";

        axios
            .get('/admin/association/'+id+'/editModal')
            .then()
            .catch()
        reloadEvent;
    }else if(crud === 'DEL'){
        modalAdhesion.querySelector('.modal-body').innerHTML = "<p>SUPPR</p>";
        reloadEvent;
    }

}

function reloadEvent()
{
    document.querySelectorAll(".btnShowModalAdhesion").forEach(function(link){link.addEventListener('click', showReco);});
}

reloadEvent()

modalAdhesion.addEventListener('hidden.bs.modal', function(){
    modalAdhesion.querySelector('.modal-title').textContent = "Adhésions";
    modalAdhesion.querySelector('.modal-body').innerHTML =
        "<div class=\"d-flex justify-content-center\">"+
        "<div class=\"spinner-border text-primary\" role=\"status\">"+
        "<span class=\"visually-hidden\">Chargement ...</span>"+
        "</div>"+
        "</div>"
    ;
})