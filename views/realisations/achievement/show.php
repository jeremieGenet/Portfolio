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
$post = (new PostTable($pdo))->find($id);

// Si le slug de l'article est différent de celui de l'url ($slug défini plus haut grâce à notre router) alors on redirige vers le slug et l'id du post original
if($post->getSlug() !== $slug){
    $url = $router->url('post', ['achievement' => $post->getSlug(), 'id' => $id]);
    http_response_code(301); // Notification de redirection d'url permanente
    header('Location: ' .$url); // Redirection vers l'url du post avec son slug et son id original (ceux dans la bdd)
}

// Récup des catégories de l'article (via l'id de l'article)
$table = new PostTable($pdo);
$categories = $table->findCategories($post->getId());
//dd($categories);
$title = $post->getName();
?>

<!-- AFFICHAGE D'UN ARTICLE (sur toute la page, après sélection de celui-ci) -->
<div class="container">
    <div class="card" style='width:100%;'>
        <div class="card-body">
            <!-- NOM -->
            <h2 class="card-title text-center"><?= htmlentities($post->getName()) ?></h2>
            <!-- DATE -->
            <p class="text-muted text-center"><?= $post->getCreatedAt_fr() ?></p>
            <!-- Lien vers les CATEGORIES -->
            <?php foreach($categories as $category): ?>
                <div class="text-center">
                    <a href="<?= $router->url('achievements-category', ['slug' => $category->getSlug(), 'id' => $category->getId()]) ?>"><?= $category->getName() ?></a>
                </div>
            <?php endforeach ?>
            <!-- IMAGE -->
            <div class="text-center mt-3">
                <img src="<?= '../assets/img/'.$post->getPicture() ?>" class="img-fluid rounded" alt="">
            </div>
            <!-- CONTENU -->
            <p class="mt-3"><?= $post->getFormatedContent() ?></p>
            <!-- LIEN (RETOUR à l'accueil du blog) -->
            <p>
                <a href="<?=  $router->url('achievements') ?>" class="btn btn-info">
                    Retour
                </a>
            </p>
        </div>
    </div>
</div>