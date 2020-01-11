<?php
/*
    FORMULAIRE HTML (qui sert à la création et à l'édition d'un article)
*/
use App\HTML\Form;

// Instanciation du formulaire de création d'un article
$form = new Form($post, $errors);
?>


<form method="POST" enctype="multipart/form-data">

    <!-- INPUT TITLE -->
    <?= $form->input('title', 'Titre'); ?>

    <!-- INPUTS CHECK-BOX (pour les catégories) Retournera le ou les id des catégories à la validation du formulaire: [0 => "1", 1 => "2", ...]  -->
    <div class="form-group">
        <label for="">Categories</label>
        <div class="form-check">

            <!-- Params: l'id de la catégorie, le nom de la catégorie, et le nom du champ (qui doit correspondre au nom de la table Category) -->
            <?php foreach($categories as $category): ?>
                <?= $form->inputCheckBox($category->getId(), $category->getName(), 'category'); ?>
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

    <!-- AFFICHAGE DE L'IMAGE ET LOGOS DU POST (si celui-ci existe) -->
    <?php if($post->getId() !== null) : ?>
        <div class="row my-3">
            <!-- IMAGE DU POST -->
            <div class="col-md-3">
                <img src="../../assets/uploads/img/<?= $post->getPicture() ?>"
                    style="width: 350px; height: 200px; background-color: rgba(100,0,255,0.2);"
                    class="rounded float-left img-thumbnail img-fluid"
                    name="<?= $post->getPicture() ?>"
                    alt="<?= $post->getPicture() ?? "Pas d'illustration !" ?>"
                >
            </div>
            <!-- AFFICHAGE DES LOGOS -->
            <div class="col-xs-12 col-md-9" style="width: 50px; height: 50px;">
                <div class="row">

                    <?php foreach($post->getLogoCollection() as $logo): ?>
                        <div class="col-xs-12 col-md-2">
                            <img src="../../assets/uploads/logo/<?= $logo->getName() ?>"
                                style="width: 90px; height: 90px;"
                                class="rounded float-left img-thumbnail img-fluid mb-2"
                                name="<?= $logo->getNameLessExt() ?>"
                                alt="<?= $logo->getNameLessExt() ?? "Pas d'illustration !" ?>"
                            >
                            <p>
                                <!-- Lien/bouton de suppression de logo (en param d'url l'id du post et l'id du logo) -->
                                <a 
                                    href="<?= $router->url('admin_post_logo_delete', ['postId' => $post->getId(), 'logoId' => $logo->getId()]) ?>" 
                                    class="btn btn-danger btn-xs" 
                                    role="button">Remove
                                </a>
                            </p>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>         
        </div>
    <?php endif ?>
    
    

    <!-- INPUT CONTENT -->
    <?= $form->textarea('content', 'Contenu'); ?>

    <!-- INPUTS LOGO (COLLECTION voir script JS) -->
    <div id="divLogos" class="form-group col-md-4 pl-0 pb-3">
        <label for="logo_0" class="inline-block">Logos</label>
            <input type="file" multiple 
            id="input_logo_0" 
            class="form-control-file"
            name="logo-collection[]"  
            is="drop-files" label="Insérer / Ajouter vos logos ici" help="plusieurs logos possibles"
            >
            <input type="hidden" name="logo-collection[]" class="form-control<?= $isInvalidLogo ?>">
            <small id="fileHelpLogo" class="form-text text-muted">Logos qui permettent d'illustrer la réalisation</small>
        <!-- Affichage de l'erreur LOGO dans une div class="invalid-feedback"-->
        <div class="invalid-feedback">
            <?php if(isset($errors['logo-collection'])): ?>  
                <?= $errors['logo-collection'] ?>
            <?php endif; ?>
        </div>
        <!--
        <button type="button" id="add_logo" class="btn btn-info btn-sm mt-3">Ajouter un logo</button>
        -->
    </div>

    <!-- INPUT DATE (si le post est déja crée) -->               
    <?php if($post->getId() !== null) : ?>
        <?= $form->input('createdAt', 'Date de création'); ?>
    <?php endif ?>

    <hr class="bg-primary my-4">

    <!-- BUTTON SOUMISSION FORMULAIRE (modification / création) -->
    <div class="d-flex justify-content-between mb-5">
        <!-- BOUTON MODIFIER/CREER -->
        <!-- Modification dynamique de l'intitulé du Bouton (Modification ou Création) -->
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

<!-- Script drop-file (module JS qui permet d'importer des images dans une zone dédiée) -->
<script type="module" src="//unpkg.com/@grafikart/drop-files-element"></script>


