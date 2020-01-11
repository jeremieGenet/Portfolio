<?php
/* 
    PAGE DE CREATION D'UN ARTILCE (post)
*/
use App\FilesManager;
use App\HTML\Notification;
use App\Models\{Post, Logo, Category};
use App\Validators\PostValidator;
use App\{Auth, Session, Connection};
use App\Table\{PostTable, LogoTable, CategoryTable};

$pdo = Connection::getPDO();
Auth::check();
$session = new Session();
$messages = $session->getFlashes('flash');

// Tableaux erreurs de formulaire ($errors regroupera les 2 autres après traitement)
$errors = []; 
$errorsPost = [];
$errorsFile = [];

// Variable utile au formulaire de collection de logos (ajoutera ou non la classe ' is-invalid' de bootstrap)
$isInvalidLogo = "";

// Récup de l'ensemble des catégories de la table Category (pour l'affichage dans le formulaire)
$category = new CategoryTable($pdo);
$categories = $category->findAll();

$post = new Post(); // Création d'un objet vide (qui contiendra le nouveau post sous forme d'objet)

// Si le formulaire est posté...
if(!empty($_POST)){
    // Pour que le nom, contenu et l'image persistent en cas d'erreurs dans le formulaire
    $post->setTitle($_POST['title']); 
    $post->setContent($_POST['content']); 
    $post->setPicture($_FILES['picture']['name']); 
    
    // VERIFICATION DES DONNEES (hors logos)
    $validate = new PostValidator($_POST, $post->getId());

    $errorsPost = $validate->fieldEmpty(['category', 'title', 'content']);
    $errorsPost = $validate->fieldLength(3, 150, ['title']);
    $errorsPost = $validate->fieldLength(5, 2000, ['content']);
    $errorsPost = $validate->fieldExist(['title']);


    // VERIFICATION DE L'IMAGE PRINCIPALE ET DE LA COLLECTION DE LOGOS ($_FILES)
    $filesManager = new FilesManager($_FILES);

    // Vérif de l'image principale ('valid()' retourne un tableau d'erreur ou un tableau vide)
    $errorsFile = $filesManager->valid('picture');

    // Si une collection de logo est postée... (s'il y en a, cette condition rend l'ajout de logos optionnel)
    if($_FILES['logo-collection']['error'][0] !== 4){ // (error 4 = vide)
        // Vérif collection de logos ('valid()' retourne un tableau d'erreur ou un tableau vide)
        $errorsFile = $filesManager->valid('logo-collection'); 
        // Condition si il y a une erreur lors de l'édition des logos (on donne la classe bootstrap " is-invalid")
        if($errorsFile !== []){
            $isInvalidLogo = ' is-invalid';
        }
    }

    // On regroupe dans un même tableau les erreurs de post ($_POST, hors $_FILES) et les erreur de fichier ($_FILES)
    $errors = array_merge($errorsPost, $errorsFile);
    //dd($errors);

    // ON PARAM LE MESSAGE FLASH DE LA SESSION (s'il y a des erreurs)
    if(!empty($errors)){
        $session->setFlash('danger', "Il faut corriger vos erreurs !"); // On crée un message flash
        $messages = $session->getFlashes('flash'); // On l'affiche
    }
    
    
    // S'il n'y a pas d'erreurs...
    if(empty($errors)){  
        // ENREGISTREMENT DES DONNEES DE L'ARTICLE (par les données postées dans le formulaire)
        $post->setTitle(htmlentities($_POST['title']));

        // Gestion des catégories
        $postTable = new PostTable($pdo);
        // Récup des catégories sous forme d'objet via leurs id (passés via le formulaire)
        $cats = $postTable->findCategoriesByid($_POST['category']); 
        foreach($cats as $cat){
            $post->setCategories($cat);
        }

        $post->setContent(htmlentities($_POST['content']));
        $post->setAuthor_id($_SESSION['user']['id']);
        $post->setLikes('0');
        $post->setIsLiked('0');


        // Upload (et rename si elle existe déjà) de l'image principale dans le dossier (retourne le nom du fichier)
        $fileName = $filesManager->transfer('picture', 'assets/uploads/img/');
        // Modif de l'image du post (avec le nom de fichier traité via la méthode "transfer()")
        $post->setPicture($fileName);

        // ENREGISTREMENT DU POST DANS LA BDD (avant la collection de logo pour récup l'id du post dans les objets logo)
        $postTable->insert($post);

        // Récup des logos postés
        $logoCollection = $_FILES['logo-collection'];
        // Si la collection de logo n'est pas vide
        if($logoCollection['error'][0] !== 4){
            // Upload (et rename si l'un d'entre eux existe déjà) de la collection de logo dans le dossier dédié (retourne les noms des fichiers)
            $logoNames = $filesManager->transfer('logo-collection', 'assets/uploads/logo/');

            // ENREGISTREMENT DE LA COLLECTION DE LOGO DANS LA BDD
            // Récup du nb de logos dans la collection postée
            $countLogos = count($logoCollection['name']);
            for($i=0; $i<$countLogos; $i++){
                // Transformation des logos reçus en objets Logo
                $logo = new Logo();
                $logo->setName($logoNames['name'][$i]);
                $logo->setSize($logoNames['size'][$i]);
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
    <hr class="bg-primary my-4">

    <!-- FORMULAIRE DE CREATION D'UN ARTICLE (via notre classe Form.php) -->
    <?php require ('_form.php') ?>

</div>