<?php
/*
    SUPPRESSION D'UN POST (traitement uniquement, utilisé pour le boutton supprimer dans post/index.php)
*/

use App\Session;
use App\Connection;
use App\Table\PostTable;


$session = new Session();

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']); // Récup du post via son id

// Suppression du post via son id
$postTable->delete($post, $params['id']);

// Création d'un message flash
$session->setFlash('success', "l'article a été supprimé !");

// Redirection vers la pages d'accueil des articles de l'administration (param pour l'affichage de message utilisateurs)
header('Location: ' . $router->url('admin_posts')); 

?>
