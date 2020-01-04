<?php
/* 
    PAGE DE CREATION D'UN ARTILCE (post)
*/
use App\FilesManager;
use App\FileTransfer;
use App\HTML\Notification;
use App\Models\{Post, Logo, Category};
use App\Validators\PostValidator;
use App\{Auth, Session, Connection};
use App\Table\{PostTable, LogoTable, CategoryTable};

$pdo = Connection::getPDO();
Auth::check();
$session = new Session();
$messages = $session->getFlashes('flash');

$errors = []; // erreurs formulaire
// Variable utile au formulaire de collection de logos (ajoutera ou non la classe ' is-invalid' de bootstrap)
$isInvalidLogo = "";

// Récup de l'ensemble des catégories de la table Category (pour l'affichage dans le formulaire)
$category = new CategoryTable($pdo);
$categories = $category->findAll();

$post = new Post(); // Création d'un objet vide (qui contiendra le nouveau post sous forme d'objet)

// Si le formulaire est posté...
if(!empty($_POST)){

    // DONNEES DU FORMULAIRE ($_POST + $_FILES)
    $data = array_merge($_POST, $_FILES);
    //dd($data);

    // Pour que le nom, contenu et l'image persistent en cas d'erreurs
    $post->setTitle($_POST['title']); 
    $post->setContent($_POST['content']); 
    $post->setPicture($_FILES['picture']['name']); 
    
    // VERIFICATION DES DONNEES (hors logos)
    $validate = new PostValidator($data, $post->getId());

    $errors = $validate->fieldEmpty(['category', 'title', 'content']);
    $errors = $validate->fieldFileEmpty(['picture']);
    $errors = $validate->fieldLength(3, 150, ['title']);
    $errors = $validate->fieldLength(5, 2000, ['content']);
    $errors = $validate->fieldExist(['title']);
    $errors = $validate->fileExist(['picture']);
    $errors = $validate->fileSize(['picture']);
    $errors = $validate->fileExtension(['picture']);
        
   
    // Récup des logos postés
    $logoCollection = $_FILES['logo-collection'];
 
    // VERIF ET TRANSFERT DES LOGOS
    // Si la collection de logo n'est pas vide ...
    if($logoCollection['error'][0] !== 4){ // (error 4 = vide)
        $validTransfertCollection = new FilesManager($logoCollection);
        $validTransfertCollection->filesValidTransfer('assets/uploads/logo');
        // Si la collection de logos (après traitement via FilesManager) ne retourne pas true (c'est qu'il y a une erreur) alors...
        if($validTransfertCollection->filesValidTransfer('assets/uploads/logo') !== true){
            $errors = $validTransfertCollection->getErrors(); // On récup les erreurs s'il y en a (voir class FileManager())
            $isInvalidLogo = ' is-invalid'; // Classe bootstrap du formulaire (pour afficher les erreurs de fichier sur la collection de logos)
        }
    }
        
    //dd($errors);
    // ON PARAM LE MESSAGE FLASH DE LA SESSION (s'il y a des erreurs)
    if(!empty($errors)){
        $session->setFlash('danger', "Il faut corriger vos erreurs !"); // On crée un message flash
        $messages = $session->getFlashes('flash'); // On l'affiche
    }
    
    // S'il n'y a pas d'erreurs...
    if(empty($errors)){  
        //dd($_POST['category']); // [ 0 => "1", 1 => "3", ...] (correspond aux id des catégories réçues via le formulaire)

        // ENREGISTREMENT DES DONNEES DE L'ARTICLE (par les données postées dans le formulaire)
        $post->setTitle(htmlentities($_POST['title']));

        // Gestion des catégories
        $postTable = new PostTable($pdo);
        // Récup des catégories sous forme d'objet via leur id (passés via le formulaire)
        $cats = $postTable->findCategoriesByid($_POST['category']); 
        foreach($cats as $cat){
            $post->setCategories($cat);
        }

        $post->setContent(htmlentities($_POST['content']));
        $post->setAuthor_id($_SESSION['user']['id']);
        $post->setLikes('0');
        $post->setIsLiked('0');

        // Transfert de l'image principale dans le dossier
        $successTransfer = FileTransfer::transfer($_FILES['picture'], 'assets/uploads/img/');
        if($successTransfer){
            // Modif de l'image du post
            $post->setPicture($_FILES['picture']['name']);
        }

        // ENREGISTREMENT DU POST DANS LA BDD (avant la collection de logo pour récup l'id du post dans les objets logo)
        $postTable->insert($post);

        // Si la collection de logo n'est pas vide
        if($logoCollection['error'][0] !== 4){
            // ENREGISTREMENT DE LA COLLECTION DE LOGO DANS LA BDD
            // Récup du nb de logos dans la collection postée
            $countLogos = count($logoCollection['name']);
            for($i=0; $i<$countLogos; $i++){
                // Transformation des logos reçus en objets Logo
                $logo = new Logo();
                $logo->setName($logoCollection['name'][$i]);
                $logo->setSize($logoCollection['size'][$i]);
                $logo->setPost_id($post->getId()); // On récup l'id du post nouvellement créé
                // Ajout des logos dans le post
                $post->addlogo($logo); // Ne sert à rien(si pas utilisé sur cette page), puisque les logos ne persitent pas dans un post 
                // Insertion des logos dans la bdd
                $logoTable = new LogoTable($pdo);
                $logoTable->insert($logo);
            }
        }
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

    <h3 class="text-center mb-4">Création d'une Réalisation</h3>
    <hr class="bg-light my-4">

    <!-- FORMULAIRE DE CREATION D'UN ARTICLE (via notre classe Form.php) -->
    <?php require ('_form.php') ?>

</div>