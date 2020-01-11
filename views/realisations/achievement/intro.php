<?php
/*
    PAGE DES REALISATIONS (Liste des réalisations)
*/
use App\Auth;
use App\Connection;
use App\Table\PostTable;

//Auth::check();

$title = 'Intro Réalisations';

$pdo = Connection::getPDO();

// On récup les résultats paginés (avec hydratation des posts (leur attribut "categories[]"))
[$posts, $pagination] = (new PostTable($pdo))->findPaginated(); // On donne les deux variables utiles au fonctionnement du script qui suit ("[$posts, $pagination]" sont les retours de la méthode findPaginated())

$link = $router->url('achievements');
//dd($posts);
?>

<div class="main-wrapper">
    <section class="cta-section theme-bg-light py-5">
        <div class="container text-center">
            <h2 class="heading">Réalisation en images</h2>
            <div class="intro">Voici quelques réalisations/projets qui m'ont permis de découvrir différentes technologies Web</div>
            
        </div><!--//container-->
    </section>


    <section class="blog-list px-3 py-5 p-md-5">

        <div class="container">

            <!-- Boucle sur l'ensemble des réalisations -->
            <?php foreach($posts as $post): ?>
                <?php require 'card.php';?>
            <?php endforeach ?>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-between my-4">
                <!-- LIEN PAGE PRECEDENTE -->
                <?= $pagination->previousLink($link) ?>
                <!-- LIEN PAGE SUIVANTE -->
                <?= $pagination->nextLink($link) ?>
            </div>
            
        </div>
    </section>
</div><!--//main-wrapper-->