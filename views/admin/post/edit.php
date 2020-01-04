<?php
/* 
    PAGE DE MODIFICATION D'UN ARTILCE (post)
*/
use App\File\{FileTransfer, FilesManager};
use App\HTML\Notification;
use App\Validators\PostValidator;
use App\{Auth, Session, Connection};
use App\Models\{Category, Post, Logo};
use App\Table\{PostTable, CategoryTable, LogoTable};

$pdo = Connection::getPDO();
Auth::check();
$session = new Session();
$messages = $session->getFlashes('flash');
$id = $params['id']; // id du post en cours de modification

$errors = []; // erreurs de formulaire
// Variable utile au formulaire de collection de logos (ajoutera ou non la classe ' is-invalid' de bootstrap)
$isInvalidLogo = "";

// Création, puis hydratation du post à modifier avec ses catégories et sa collection de logo (via son id)
$getPost = new Post();
$post = $getPost->hydrate($id);
//dd($post, $post->getCategories(), $post->getLogoCollection()); // Post hydraté !

// Récup de l'ensemble des catégories de la table Category (pour l'affichage dans le formulaire)
$category = new CategoryTable($pdo);
$categories = $category->findAll();

// Si le formulaire est validé...
if(!empty($_POST)){

    // Pour que le nom, et contenu persistent en cas d'erreurs formulaire
    $post->setTitle($_POST['title']); 
    $post->setContent($_POST['content']); 
    
    // DONNEE DU FORMULAIRE ($_POST + $_FILES)
    $data = array_merge($_POST, $_FILES);
    //dd($data);

    // VERIFICATION DES DONNEES
    $validate = new PostValidator($data, $post->getId());

    $errors = $validate->fieldEmpty(['title', 'content']);
    $errors = $validate->fieldLength(3, 50, ['title']);
    $errors = $validate->fieldLength(5, 2000, ['content']);
    $errors = $validate->fieldExist(['title']);
    $errors = $validate->fileExist(['picture']);
    $errors = $validate->fileSize(['picture']);
    $errors = $validate->fileExtension(['picture']);

    
    // Récup des logos postés
    $logoCollection = $_FILES['logo-collection'];
    //dd($logoCollection);
 
    // VERIF ET UPLOAD DES LOGOS
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


    // ON PARAM LE MESSAGE FLASH DE LA SESSION (s'il y a des erreurs)
    if(!empty($errors)){
        $session->setFlash('danger', "Il faut corriger vos erreurs !"); // On crée un message flash
        $messages = $session->getFlashes('flash'); // On l'affiche
    }

    // MODIFICATION DES DONNEES DE L'ARTICLE (par les données postées dans le formulaire)
    // S'il n'y a pas d'erreurs...
    if(empty($errors)){
        $post->setTitle(htmlentities($_POST['title']));

        // Gestion de la modification des catégories
        // Si les catégories postées ne sont pas vide ...
        if(!empty($_POST['category'])){ // Choix de Catégories non obligatoire lors de l'édition d'un post
            // Récup des catégories sous forme d'objet via leur id
            $postTable = new PostTable($pdo);
            $cats = $postTable->findCategoriesByid($_POST['category']);
            // On retire les categories présentes dans le post (dans le but d'en ajouter de nouvellles)
            $post->removeCategories();
            // Ajout des nouvelles catégories reçues (via le formulaire)
            foreach($cats as $cat){
                $post->setCategories($cat);
            }
        }

        // ENREGISTREMENT DE LA COLLECTION DE LOGO DANS LA BDD
        if($logoCollection['error'][0] !== 4){ // Si la collection de logo n'est pas vide
            // Récup du nb de logos dans la collection postée
            $countLogos = count($logoCollection['name']);
            for($i=0; $i<$countLogos; $i++){
                // On vide la collection de logos
                $post->removeCollectionLogo();

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

        $post->setContent($_POST['content']);  
        $post->setCreatedAt($_POST['createdAt']);

        //dd($post->getPicture(), $_FILES['picture']['name']);

        // TRAITEMENT DE L'IMAGE PRINCIPALE (upload, et suppression de l'ancienne puis enregistrement dans le post)
        // Si l'image actuelle du post est différente de l'image postée et que l'image postée est différente de vide ("") alors...
        if(($post->getPicture() !== $_FILES['picture']['name']) && ($_FILES['picture']['name']) !== ""){
            // Dossier dans lequel on dirige l'image postée
            $pathImages = 'assets/uploads/img/'; 
            // Transfert de l'image dans le dossier
            $successTransfer = FileTransfer::transfer($_FILES['picture'], $pathImages);
            // On supprime le fichier (ex : 'assets/img/haru.jpg') du post actuel s'il existe 
            if(file_exists($pathImages . $post->getPicture())){
                unlink($pathImages . $post->getPicture());
            }else{
                $session->setFlash('warning', "Le fichier n'a pas été supprimé !!!"); // On crée un message flash
                $messages = $session->getFlashes('flash'); // On l'affiche
            }
            
            if($successTransfer){
                // Modif de l'image du post
                $post->setPicture($_FILES['picture']['name']);
            }
        }
        // MODIFICATION DU POST DANS LA BDD , puis message flash et redirection
        $postTable = new PostTable($pdo);
        $postTable->update($post, $id); // $id = "$params['id']" qui est l'id du post à modifié (récup via les param altorouter)
        // Param du message flash de SESSION, puis redirection
        $session->setFlash('success', "Modification réussie !!!!");
        header('Location: ' . $router->url('admin_posts', ['id' => $post->getId()]));
        exit;
    }
    
}

?>

<!-- EDITION D'UN ARTICLE (post)-->
<div class="container">
    <h3 class="text-center">Edition de l'article : <strong><?= htmlentities($post->getTitle()) ?></strong></h3>

    <!-- Notification Utilisateur (messages flash) -->
    <?= Notification::toast($messages) ?>

    <!-- AFFICHAGE DES CATEGORIES -->
    <hr class="bg-light my-4">
    <h5 class="text-center mb-3">Catégorie(s)</h5>
    <?php //dd($post->getCategories()); ?>
    
        <?php foreach($post->getCategories() as $cat): ?>
            <?php //dd($post->getCategories()); ?>
            <p class="text-center"> <?= $cat->getName() ?></p>
        <?php endforeach; ?>
    
    <hr class="bg-light my-4">

    <!-- FORMULAIRE D'EDITION DE L'ARTICLE (via notre classe Form.php) -->
    <?php require ('_form.php') ?>
</div>