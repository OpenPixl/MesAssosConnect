// Appel des constantes

// Functions
const tsChoiceMenber = new TomSelect("#adhesion_members", {
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

const tsChoiceAdhesion = new TomSelect("#adhesion_typeAdhesion", {
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

const tsChoiceAsso = new TomSelect("#adhesion_asso", {
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

// Liste des events