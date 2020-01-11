<?php
// PAGE D'AFFICHAGE D'UNE REALISATION

use App\Auth;
use App\Connection;
use App\Table\PostTable;

//Auth::check();

$id = (int)$params['id'];
$slug = $params['slug'];


// Connextion à la bdd
$pdo = Connection::getPDO();
// Récup du post (via son id passé en param dans l'url)
$postTable = new PostTable($pdo);
$post = $postTable->find($id);
//dd($post);

// Hydratation du post (ajout de la collection de logo et des catégories)
$logos = $postTable->findLogoCollection($id);
foreach($logos as $logo){
    //dd($logo);
    $post->addLogo($logo);
}
$categories = $postTable->findCategories($id);
foreach($categories as $category){
    $post->setCategories($category);
}
//dd($post);

// Si le slug de l'article est différent de celui de l'url ($slug défini plus haut grâce à notre router) alors on redirige vers le slug et l'id du post original
if($post->getSlug() !== $slug){
    $url = $router->url('achievement', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301); // Notification de redirection d'url permanente
    header('Location: ' .$url); // Redirection vers l'url du post avec son slug et son id original (ceux dans la bdd)
}

// Récup des catégories de l'article (via l'id de l'article)
$table = new PostTable($pdo);
$categories = $table->findCategories($post->getId());
//dd($categories);
$title = $post->getTitle();
?>

<!-- AFFICHAGE D'UN ARTICLE (sur toute la page, après sélection de celui-ci) -->
<div class="container">
    <div class="jumbotron">
    <!-- NOM (titre) -->
    <h1 class="text-center"><?= $title ?></h1>
    <!-- DATE -->
    <p class="text-muted text-center"><?= $post->getCreatedAt_fr() ?></p>
    <!-- Liens vers les CATEGORIES -->
    <?php foreach($categories as $category): ?>
        <div class="text-center">
            <a href="<?= $router->url('achievements-category', ['slug' => $category->getSlug(), 'id' => $category->getId()]) ?>"><?= $category->getName() ?></a>
        </div>
    <?php endforeach ?>
    <!-- IMAGE -->
    <div class="text-center mt-3">
        <img src="<?= '../assets/uploads/img/'.$post->getPicture() ?>" 
        class="img-fluid rounded" 
        alt="<?= $post->getPicture() ?>">
    </div>
    
    <!-- LOGOS -->
    <div class="row mt-4">
        <div class="col-md-4">
            <!-- Titre vide -->
            <h4></h4>
        </div>
        <div class="col-md-6">
            
            <?php foreach($post->getLogoCollection() as $logo): ?>
                
                <img src="../../assets/uploads/logo/<?= $logo->getName() ?>"
                    style="width: 60px; height: 60px;"
                    class="rounded float-left img-thumbnail img-fluid"
                    name="<?= $logo->getNameLessExt() ?>"
                    alt="<?= $logo->getNameLessExt() ?? "Pas d'illustration !" ?>"
                >
                <?php //dd($logo->getNameLessExt()); ?>
            <?php endforeach; ?>
        </div>
    </div>
    <hr class="my-4">
    <!-- CONTENU -->
    <p class="mt-3"><?= $post->getFormatedContent() ?></p>
    <hr class="my-4">
    <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
    <hr class="my-4">
    <p>
        <a href="<?=  $router->url('achievements') ?>" class="btn btn-primary">
            Retour
        </a>
    </p>
    </div>
</div>
