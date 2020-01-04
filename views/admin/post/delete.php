<?php
/*
    PAGE DE SUPPRESSION D'UN POST (traitement uniquement)
*/

use App\Auth;
use App\Session;
use App\Connection;
use App\Table\PostTable;


Auth::check();

$session = new Session();

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
//dd($params['id']);
$postTable->delete($post, $params['id']);


$session->setFlash('success', "l'article a été supprimé !");


// Redirection vers la pages d'accueil des articles de l'administration (param pour l'affichage de message utilisateurs)
header('Location: ' . $router->url('admin_posts')); 

?>
