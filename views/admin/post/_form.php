<?php
/*
    FORMULAIRE HTML (qui sert à la création et à l'édition d'un article)
*/
use App\HTML\Form;


// Instanciation du formulaire de création d'un article
$form = new Form($post, $errors);
//dd(isset($errors['category']));

//dd($categoriesById); // ICI JE VEUX UN TABLEAU DES NOMS DES CATEGORIES (["1" => 'JeuxVideo', "3" => 'Console de jeux',...])
//dd($post->getId());
?>
<form action="" method="POST" enctype="multipart/form-data">

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

    <!-- AFFICHAGE DE L'IMAGE DU POST (si celui-ci existe) -->
    <?php if($post->getId() !== null) : ?>
        <div class="mh-100 mb-3" style="width: 350px; height: 200px; background-color: rgba(100,0,255,0.2);">
            <img src="../../assets/img/<?= $post->getPicture() ?>"
                class="rounded float-left img-thumbnail img-fluid"
                name="<?= $post->getPicture() ?>"
                alt="<?= $post->getPicture() ?? "Pas d'illustration !" ?>"
            >
        </div>
    <?php endif ?>

    <?= $form->inputFile('picture', "Nom de l'image : ".$post->getPicture()); ?>
    <?= $form->textarea('content', 'Contenu'); ?>

    <?= $form->inputLogos('logo', 'Logos'); ?>

    <!-- Si il y a un post de crée (si celui-ci existe) -->
    <?php if($post->getId() !== null) : ?>
        <?= $form->input('createdAt', 'Date de création'); ?>
    <?php endif ?>


    <div class="d-flex justify-content-between mb-4">
        <!-- BOUTON MODIFIER/CREER -->
        <!-- Modification dynamique de l'intitulé du Bouton (Modification ou création) -->
        <button class="btn btn-success">
            <!-- Si l'article à un id qui n'est pas null (donc l'article existe) alors... -->
            <?php if($post->getId() !== null) : ?>
                Modifier
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
            input.name = 'logo'+IDnb; ///////////////////////// NOM DE L'INPUT (qui permet de récup les infos postées) ////////////////////////
        const buttonRemove = document.createElement("button");
            buttonRemove.id = 'remove_logo_' + IDnb;
            buttonRemove.className = "btn btn-danger float-right";
            buttonRemove.textContent = 'X';
            buttonRemove.type = "button";
            buttonRemove.name = "remove_logo"
        divFormLogo.appendChild(input);
        divFormLogo.appendChild(buttonRemove);

        // BOUCLE SUR TOUT LES BOUTONS NAME 'remove_logo'
        buttons = document.getElementsByName('remove_logo');
        // EVENT lors du click pour supprimer la div qui contient le bouton supprimer
        buttons.forEach(function(button){
            button.addEventListener('click', function (e){
                //console.log(button.id);
                divAsupprimer = document.getElementById(button.id.substr(7)); // Récup de la div à supprimer (correspond à l'id du bouton sans le 'remove_' de son id)
                //console.log(button.id.substr(7));
                //console.log(divAsupprimer);
                divLogos.removeChild(divAsupprimer);
            })
        })

    });

</script>