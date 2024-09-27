const modalAdhesion = document.getElementById("modalAdhesion");
const modalAdhesionBS = new bootstrap.Modal(modalAdhesion);
const btnShowAdhesion = document.querySelectorAll(".btnShowModalAdhesion");

// Tableau pour stocker les valeurs des checkboxes cochées
let selectedValues = [];
// Sélectionne toutes les checkboxes de la page
const tables = document.querySelectorAll('.table');



// Fonctions
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
                    // Vérifie si la checkbox est cochée
                    if (checkbox.checked) {
                        // Ajoute la valeur de la checkbox au tableau si elle est cochée
                        if (!selectedValues.includes(checkbox.value)) {
                            selectedValues.push(checkbox.value);
                        }
                    } else {
                        // Supprime la valeur du tableau si la checkbox est décochée
                        const index = selectedValues.indexOf(checkbox.value);
                        if (index > -1) {
                            selectedValues.splice(index, 1);
                        }
                    }
                }
            }
        });
    })
}

function showAdhesion() {
    let opt = this.getAttribute('data-bs-whatever');
    let crud = opt.split('-')[0];
    let contentTitle = opt.split('-')[1];
    let id = opt.split('-')[2]
    document.getElementById('modalAdhesion').querySelector('.modal-title').textContent = contentTitle;
    if(crud === 'ADD'){
        modalAdhesionBS.show();
        modalAdhesion.querySelector('.modal-body').innerHTML = "<p>AJOUT</p>";
        axios
            .get('/gestion/adhesion/newOnMember/' + id)
            .then(function(response){
                modalAdhesion.querySelector('.modal-body').innerHTML = response.data.formView;
                tomSelect('#adhesion_on_member_members');
                loadEvents();
            })
            .catch()
    }else if(crud === 'EDIT'){
        modalAdhesionBS.show();
        modalAdhesion.querySelector('.modal-body').innerHTML = "<p>MODIF</p>";

        axios
            .get('/gestion/adhesion/'+id+'/editOnMember')
            .then()
            .catch()
        loadEvents();
    }else if(crud === 'DEL'){
        modalAdhesion.querySelector('.modal-body').innerHTML = "<p>SUPPR</p>";
        loadEvents();
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

function loadEvents()
{
    tableCheckboxValues(tables, selectedValues);
    btnShowAdhesion.forEach(function(link){link.addEventListener('click', showAdhesion);});
}

loadEvents()

modalAdhesion.addEventListener('hidden.bs.modal', function(){
    modalAdhesion.querySelector('.modal-title').textContent = "Adhésions";
    modalAdhesion.querySelector('.modal-body').innerHTML =
        "<div class=\"d-flex justify-content-center\">"+
        "<div class=\"spinner-border text-primary\" role=\"status\">"+
        "<span class=\"visually-hidden\">Loading...</span>"+
        "</div>"+
        "</div>"
    ;
})
