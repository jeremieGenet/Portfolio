<?php
/*
    PAGE D'INSCRIPTION D'UN NOUVEAU USER (Création de compte)
*/
use App\Auth;

use App\Session;
use App\HTML\Form;
use App\Connection;
use App\Models\User;
use App\Table\UserTable;
use App\Validators\UserValidator;

//Auth::check();

$pdo = Connection::getPDO();
$session = new Session();
$userTable = new UserTable($pdo);
$user = new User();
$errors = [];


if(!empty($_POST)){

    $data = $_POST;
    //dd($data);

    $user->setUsername($_POST['username']); // Pour que le champs username reste rempli même si il y a une erreur

    
    $validate = new UserValidator($data);

    $errors = $validate->fieldEmpty(['username', 'password']);
    $errors = $validate->fieldLength(['username'], 2, 20); // 4 caractères minimum pour le mot de passe
    $errors = $validate->fieldLength(['password'], 4, 30); // 4 caractères minimum pour le mot de passe
    $errors = $validate->samePassword($_POST['password'], $_POST['passwordConfirm']); // Vérif si le password et le passwordConfirm sont les mêmes
    $errors = $validate->passwordVulnerability($_POST['password']); // Permet de Sécurisé le mot de passe (param 2 à null par défaut, mais peu $être : 'max', "middle", "mini")

    //dd($errors);
    if(empty($errors)){
        // MODIFICATION DES DONNEES de l'utilisateur par les données postées dans le formulaire (de modification de l'article)
        $user->setUsername(htmlentities($_POST['username']));
        $user->setPassword(password_hash(($_POST['password']), PASSWORD_BCRYPT));

        // ENREGISTREMENT DES DONNEES DANS LA BDD
        $userTable->insert($user);
        
        // RETRAVAILLER LA REDIRECTION ET LE MESSAGE FEEDBACK UTILISATEUR
        $session->setFlash('success', "Vous êtes maintenant enregistrer, connectez vous !");
        header('Location: ' . $router->url('login'));
    }
    
}



$form = new Form($user, $errors);
?>

<div class="container">

    <h1>S'inscrire</h1>

    <!-- MESSAGE UTILISATEUR DANGER -->
    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            L'article n'a pas pu être enregistré, merci de corriger vos erreurs
        </div>
    <?php endif; ?>

    <!-- FORMULAIRE -->
    <form action="" method="POST">
    
        <?= $form->input('username', 'Nom d\'utilisateur'); ?>
        <?= $form->inputPassword('password', 'password'); ?>
        <?= $form->inputPasswordConfirm('passwordConfirm', 'Confirmation de mot de passe'); ?>
        

        <button type="submit" class="btn btn-success">S'enregister</button>
        

    </form>

</div>

