<?php
/*
    PAGE D'ACCUEIL DE L'ADMINISTRATION
*/
use App\{Auth, Session};
use App\HTML\Notification;


// Vérif si l'utilisateur est autorisé à accéder à cette page
Auth::check(); 
// Lecture puis suppression des message Flash de la session (lors de la connexion au site)
$session = new Session();
$messages = $session->getFlashes('flash');

//dd($messages);
?>

<div class="container">

    <!-- Notification Utilisateur -->
    <?= Notification::toast($messages) ?>
  

    <h1>Accueil de l'Administration du site</h1>


</div>


