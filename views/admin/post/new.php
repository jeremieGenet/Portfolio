<?php
/* 
    PAGE DE CREATION D'UN ARTILCE (post)
*/
use App\Models\{Post, Logo};
use App\FileTransfer;
use App\Table\{PostTable, LogoTable};
use App\HTML\Notification;
use App\Table\CategoryTable;
use App\Validators\PostValidator;
use App\{Auth, Session, Connection};

Auth::check();
$session = new Session();
$messages = $session->getFlashes('flash');

$pdo = Connection::getPDO();

// On récup les catégories indéxées par leur propre id (FETCH_KEY_PAIR) (["1" => 'JeuxVideo', "3" => 'Console de jeux',...])
$category = new CategoryTable($pdo);
$categoriesById = $category->findById();

$postTable = new PostTable($pdo);
$post = new Post(); // Création d'un objet vide (qui contiendra les données de notre new post)
$logoTable = new LogoTable($pdo);

$errors = [];

// Variables utiles au formulaire de collection de logos
$names = [];
$isInvalidLogo = "";

// Si le formulaire est posté...
if(!empty($_POST)){

    // Pour que le nom, categories, contenu et l'image persistent en cas d'erreurs
    $post->setName($_POST['name']); 
    $post->setCategories($_POST['category']);
    $post->setContent($_POST['content']); 
    $post->setPicture($_FILES['picture']['name']); 


    // DONNEE DU FORMULAIRE ($_POST + $_FILES)
    $data = array_merge($_POST, $_FILES);
    // VERIFICATION DES DONNEES (hors logos)
    $validate = new PostValidator($data, $post->getId());

    $errors = $validate->fieldEmpty(['category', 'name', 'content']);
    $errors = $validate->fieldFileEmpty(['picture']);
    $errors = $validate->fieldLength(3, 150, ['name']);
    $errors = $validate->fieldLength(5, 2000, ['content']);
    $errors = $validate->fieldExist(['name']);
    $errors = $validate->fileExist(['picture']);
    $errors = $validate->fileSize(['picture']);
    $errors = $validate->fileExtension(['picture']);
        
    // VERIFICATION DES LOGOS (Collection)
    if($_FILES['logo_0']['error'] !== 4){ // error 4 = vide
        // Récup des logos (ON RETIRE "picture")
        $dataLogo = $_FILES;
        unset($dataLogo[array_search($_FILES['picture'], $dataLogo)]);
        // Récup des noms (name des input file) des logos (logo_0, logo_1...)
        $names = array_keys($dataLogo);

        // Boucle sur les nom des logos reçus (logo_0, logo_1...)
        foreach($names as $name){
            $errors = $validate->fileSize([$name]);
            $errors = $validate->fileExtension([$name]);
            if(isset($errors[$name])){
                $isInvalidLogo = ' is-invalid';
            }
        }
    }
    
    // ON PARAM LE MESSAGE FLASH DE LA SESSION (s'il y a des erreurs)
    if(!empty($errors)){
        $session->setFlash('danger', "Il faut corriger vos erreurs !"); // On crée un message flash
        $messages = $session->getFlashes('flash'); // On l'affiche
    }
    
    // S'il n'y a pas d'erreurs...
    if(empty($errors)){  
        // MODIFICATION DES DONNEES DE L'ARTICLE (par les données postées dans le formulaire)
        $post->setName(htmlentities($_POST['name']));
        $post->setCategories($_POST['category']); 
        $post->setContent(htmlentities($_POST['content']));
        $post->setLikes('0');
        $post->setIsLiked('0');

        // Transfert de l'image dans le dossier
        $successTransfer = FileTransfer::transfer($_FILES['picture'], 'assets/upload/img/');
        if($successTransfer){
            // Modif de l'image du post
            $post->setPicture($_FILES['picture']['name']);
        }

        // TRAITEMENT DES LOGOS (transfert fichiers, tranformation en objet Logo, et Insertion dans la bdd)
        foreach($names as $name){
            // Transfert des logos dans le dossier
            $successTransfer = FileTransfer::transfer($dataLogo[$name], 'assets/upload/logo/');
            if($successTransfer){
                // Transformation des logos reçus en objets Logo
                $logo = new Logo();
                $logo->setName($dataLogo[$name]['name']);
                $logo->setSize($dataLogo[$name]['size']);
                // Ajout des logos dans le post
                $post->addlogo($logo);
                // Insertion des logos dans la bdd
                $logoTable->insert($logo);
            }
            
        }

        // ENREGISTREMENT DU POST DANS LA BDD
        $postTable->insert($post);
        // Param du message flash de SESSION, puis redirection
        $session->setFlash('success', "L'article est crée !");
        header('Location: ' . $router->url('admin_posts'));
    }

}
?>

<!-- CREATION D'UN ARTICLE (post)-->
<div class="container">

    <!-- Notification Utilisateur -->
    <?= Notification::toast($messages) ?>

    <h1 class="text-center mb-4">Création d'un article</h1>

    <!-- FORMULAIRE DE CREATION D'UN ARTICLE (via notre classe Form.php) -->
    <?php require ('_form.php') ?>

</div>