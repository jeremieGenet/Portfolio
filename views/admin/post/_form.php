<?php
/*
    FORMULAIRE HTML (qui sert à la création et à l'édition d'un article)
*/
use App\HTML\Form;

// Instanciation du formulaire de création d'un article
$form = new Form($post, $errors);

?>

<form action="" method="POST" enctype="multipart/form-data">

    <!-- INPUT NAME -->
    <?= $form->input('name', 'Titre'); ?>
    <!-- INPUTS CHECK-BOX (pour les catégories) le résultat d'une box retourne : "name" => "value" -->
    <div class="form-group">
        <label for="">Categories</label>
        <div class="form-check">
            <?php foreach($categoriesById as $id => $category): ?>
                <?= $form->inputCheckBox($id, $category, 'category'); ?>
            <?php endforeach;?>
            <div class="invalid-feedback">
                <?php if(isset($errors['category'])): ?> 
                    <?= $errors['category'] ?>
                <?php endif; ?>
            </div>
        
        </div>
    </div>

    <!-- INPUT PICTURE -->
    <?= $form->inputFile('picture', "Nom de l'image : ".$post->getPicture()); ?>

    <!-- AFFICHAGE DE L'IMAGE DU POST (si celui-ci existe) -->
    <?php if($post->getId() !== null) : ?>
        <div class="mh-100 mb-3" style="width: 350px; height: 200px; background-color: rgba(100,0,255,0.2);">
            <img src="../../assets/upload/img/<?= $post->getPicture() ?>"
                class="rounded float-left img-thumbnail img-fluid"
                name="<?= $post->getPicture() ?>"
                alt="<?= $post->getPicture() ?? "Pas d'illustration !" ?>"
            >
        </div>
    <?php endif ?>

    <!-- INPUT CONTENT -->
    <?= $form->textarea('content', 'Contenu'); ?>
    <!-- INPUTS LOGO (COLLECTION voir script JS) -->
    <div id="divLogos" class="form-group col-md-4 pl-0 pb-3">
        <label for="logo_0" class="inline-block">Logos</label>
            <input type="file" id="input_logo_0" class="form-control-file<?= $isInvalidLogo ?>" name="logo_0" value="dsqdfqsd"  aria-describedby="fileHelpLogo">
            <small id="fileHelpLogo" class="form-text text-muted">Logo qui permet d'illustrer la réalisation (plusieurs logos si un premier est renseigné)</small>
        <!-- Affichage de l'erreur LOGO dans une div class="invalid-feedback"-->
        <div class="invalid-feedback">
            <?php foreach($names as $name): ?>
                <?php if(isset($errors[$name])): ?>  
                    <?= $errors[$name] ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add_logo" class="btn btn-info btn-sm mt-3">Ajouter un logo</button>
    </div>

    
    <!-- INPUT DATE (si le post est déja crée) -->               
    <?php if($post->getId() !== null) : ?>
        <?= $form->input('createdAt', 'Date de création'); ?>
    <?php endif ?>

    <!-- BUTTON SOUMISSION FORMULAIRE (modification / création) -->
    <div class="d-flex justify-content-between mb-4">
        <!-- BOUTON MODIFIER/CREER -->
        <!-- Modification dynamique de l'intitulé du Bouton (Modification ou création) -->
        <button class="btn btn-success">
            <!-- Si l'article à un id qui n'est pas null (donc l'article existe) alors... -->
            <?php if($post->getId() !== null) : ?>
                Modification
            <!-- Sinon l'article n'existe pas alors ... -->
            <?php else: ?>
                Création
            <?php endif ?>
        </button>
        <!-- BOUTON DE RETOUR -->
        <a href="<?= $router->url('admin_posts') ?>" class="btn btn-primary ml-auto">Retour &raquo;</a>
    </div>
    
</form>

<!-- CREATION DES BOUTON D'AJOUT DE LOGO -->
<script>

    const buttonAdd = document.getElementById('add_logo'); // Récup du bouton 'Ajouter un logo'
    const divLogos = document.getElementById('divLogos'); // Récup de la div qui contient les logos

    let IDnb = 0; // permet la création d'id pour les éléments à créer

    // EVENT lors du click du botton 'Ajouter un logo'
    buttonAdd.addEventListener("click", function (e) {

        IDnb++;

        // Création d'une div qui va contenir chaque nouveau logo créé (c'est cette div qui est supprimée lorsqu'on click sur le bouton rouge)
        const divFormLogo = document.createElement('div');
            divFormLogo.id = 'logo_' + IDnb;
            divFormLogo.className = "form-group";
        divLogos.insertBefore(divFormLogo, buttonAdd);
        
        // Création de l'input type "file" et de son bouton de suppression
        const input = document.createElement("input");
            input.id = 'input_logo_' + IDnb;
            input.type = 'file';
            input.className = 'form-control-file'
            input.name = 'logo_'+ IDnb; ///////////////////////// NOM DE L'INPUT (qui permet de récup les infos postées) ////////////////////////
        const buttonRemove = document.createElement("button");
            buttonRemove.id = 'remove_logo_' + IDnb;
            buttonRemove.className = "btn btn-danger float-right";
            buttonRemove.textContent = 'X';
            buttonRemove.type = "button";
            buttonRemove.name = "remove_logo"
        divFormLogo.appendChild(input);
        divFormLogo.appendChild(buttonRemove);

        // BOUCLE SUR TOUT LES BOUTONS NAME 'remove_logo'
        let buttons = document.getElementsByName('remove_logo');
        //console.log('alors?');
        //console.log(buttons.length + 1); /********************************** NB DE BOUTTON A SUPPRIMER + 1 (celui de départ) ***************************************** */

        //console.log(IDnb, 'id avant suppression');
        // EVENT lors du click pour supprimer la div qui contient le bouton supprimer
        buttons.forEach(function(button){
            button.addEventListener('click', function (e){

                //console.log(IDnb--, 'id après suppression!!!');

                //console.log(button.id);
                
                let buttonAsupprimer = button.id.substr(7);
                //console.log(typeof buttonAsupprimer);
                divAsupprimer = document.getElementById(buttonAsupprimer); // Récup de la div à supprimée (correspond à l'id du bouton sans le 'remove_' de son id)
                //console.log(button.id.substr(7));
                console.log(typeof divAsupprimer); // OBJECT
                //console.log(typeof buttonAsupprimer); // STRING
                divLogos.removeChild(divAsupprimer);
            })
        })

    });

    // SUPPRESSION DU CONTENU (value) DE L'INPUT DU PREMIER LOGO (id = input_logo_0)
    // Création d'un bouton de suppression du 1er Logo
    var inputMaster = document.getElementById('input_logo_0'); // input dans lequel on veut supprimer la valeur
    const fileHelp = document.getElementById('fileHelpLogo');

    const buttonRemoveMaster = document.createElement("button");
        buttonRemoveMaster.id = 'remove_logo_0';
        buttonRemoveMaster.className = "btn btn-danger float-right";
        buttonRemoveMaster.textContent = 'X';
        buttonRemoveMaster.type = "button";
        buttonRemoveMaster.name = "remove_logo"
    divLogos.insertBefore(buttonRemoveMaster, fileHelp);

    // EVENT sur le bouton de suppression du 1er Logo
    buttonRemoveMaster.addEventListener("click", function (e) {
        inputMaster = document.getElementById('input_logo_0'); // input dans lequel on veut supprimer la valeur
        console.log(inputMaster.value);
        // Si il y a une value, on la supprimer ("")
        if(inputMaster.value){
            inputMaster.value = "";
        }
    });

</script>