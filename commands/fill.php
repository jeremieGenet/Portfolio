<?php

use App\Connection;

require dirname(__DIR__) . '/vendor/autoload.php';

// Initialisation de la librairie Faker (en français)
$faker = Faker\Factory::create('fr_FR');

// Connexion à la bdd
$pdo = Connection::getPDO();

// PREPARATION DES TABLE AVANT DE LES REMPLIR 
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0'); // On supprime les clés étrangères (pour les tables qui ont des liens entre elles)
// On vide les tables de la bdd
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE post_logo');
$pdo->exec('TRUNCATE TABLE logo');
$pdo->exec('TRUNCATE TABLE user');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1'); // On remet les clés étrangères (après la suppression de toute donnée des tables)

// Tableaux qui vont contenir les id des table post et category (dans le but de les utiliser pour remplir la table post_category)
$posts = [];
$categories = [];

// REMPLISSAGE DES TABLES (Pour executer la requête, taper dans l'invite de commande: php commands/fill.php)
// Boucle de 10 pour remplir la table post

for($i=0; $i<10; $i++){
    // TABLE post
    $pdo->exec("INSERT INTO post SET 
        picture='{$i}', 
        name='{$faker->sentence()}', 
        slug='{$faker->slug}', 
        content='{$faker->paragraphs(rand(3, 15), true)}',
        created_at='{$faker->date} {$faker->time}', 
        likes = '{$faker->randomDigit}',
        isLiked = '{$faker->numberBetween(0, 1)}'
    ");
    // On insère dans le tableau le dernier id enregistré dans la bdd (à chaque tour de boucle va remplir le tableau de l'id de l'entrée)
    $posts[] = $pdo->lastInsertId(); // "$posts" va récupérer l'ensemble des id des articles (post)
}

// Boucle de 5 pour remplir la table category
for($i=0; $i<5; $i++){
    // TABLE category
    $pdo->exec("INSERT INTO category SET 
        name='{$faker->sentence(3)}', 
        slug='{$faker->slug}'
    ");
    // On insère dans le tableau le dernier id enregistré dans la bdd (à chaque tour de boucle va remplir le tableau de l'id de l'entrée)
    $categories[] = $pdo->lastInsertId(); 
}


// Boucle de remplissage de la table post_category
foreach($posts as $post){
    // $randomCategories contiendra des catégories 'fake' entre 0 et le nb de categories existantes
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories))); 
    foreach($randomCategories as $category){
        $pdo->exec("INSERT INTO post_category SET post_id = '$post', category_id = '$category' ");
    }
}
    
// Remplissage de la table user (avec admin admin)
$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET userName = 'admin', email = 'admin@admin.fr', password = '$password' ");

