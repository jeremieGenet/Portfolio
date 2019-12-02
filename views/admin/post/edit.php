<?php
/* 
    PAGE DE MODIFICATION D'UN ARTILCE (post)
*/
use App\FileTransfer;
use App\HTML\Notification;
use App\Validators\PostValidator;
use App\{Auth, Session, Connection};
use App\Table\{PostTable, CategoryTable};


Auth::check();
$session = new Session();
$messages = $session->getFlashes('flash');

$id = $params['id'];
$pdo = Connection::getPDO();

// On récup les catégories indéxées par leur propre id (FETCH_KEY_PAIR) (["1" => 'JeuxVideo', "3" => 'Console de jeux',...])
$category = new CategoryTable($pdo);
$categoriesById = $category->findById();

$postTable = new PostTable($pdo);
$post = $postTable->find($id); // Récup du post avec ses infos (avant modification) via l'id passé dans l'url
$errors = []; // erreurs de formulaire

// Si le formulaire est validé...
if(!empty($_POST)){

    $post->setName($_POST['name']); // pour que le nom du post reste en cas d'erreurs
    $post->setContent($_POST['content']); // pour que le contenu du post reste en cas d'erreurs
    
    // DONNEE DU FORMULAIRE ($_POST + $_FILES)
    $data = array_merge($_POST, $_FILES);
    /*
    $data = [
        "name" => "Ann la panthere",
        "category" => [
            '0' => 'Heroine',
            '1' => 'Team'
        ]
        "content" => "qsdfqsdf",
        "createdAt" => "2019-11-03 18:53:57",
        "picture" => [
            "name" => "ann2.jpg",
            "type" => "image/jpeg",
            "tmp_name" => "D:\Code\xampp\tmp\phpBEE6.tmp",
            "error" => 0,
            "size" => 192429
        ]
    ];
    */
    //dd($data);

    // VERIFICATION DES DONNEES
    $validate = new PostValidator($data, $post->getId());

    $errors = $validate->fieldEmpty(['name', 'content']);
    $errors = $validate->fieldLength(3, 50, ['name']);
    $errors = $validate->fieldLength(5, 2000, ['content']);
    $errors = $validate->fieldExist(['name']);
    $errors = $validate->fileExist(['picture']);
    $errors = $validate->fileSize(['picture']);
    $errors = $validate->fileExtension(['picture']);

    // ON PARAM LE MESSAGE FLASH DE LA SESSION (s'il y a des erreurs)
    if(!empty($errors)){
        $session->setFlash('danger', "Il faut corriger vos erreurs !"); // On crée un message flash
        $messages = $session->getFlashes('flash'); // On l'affiche
    }

    // S'il n'y a pas d'erreurs...
    if(empty($errors)){
        // MODIFICATION DES DONNEES DE L'ARTICLE (par les données postées dans le formulaire) 
        $post->setName($_POST['name']);
        if(isset($_POST['category'])){ // Choix de Catégories non obligatoire lors de l'édition d'un post
            $post->setCategories($_POST['category']);
        }
        $post->setContent($_POST['content']);  
        $post->setCreatedAt($_POST['createdAt']);

        // Si l'image actuelle du post est différente de l'image postée et que l'image postée est différente de vide ("") alors...
        if(($post->getPicture() !== $_FILES['picture']['name']) && ($_FILES['picture']['name']) !== ""){
            // Dossier dans lequel on dirige l'image postée
            $pathImages = 'assets/img/'; 
            // Transfert de l'image dans le dossier
            $successTransfer = FileTransfer::transfer($_FILES['picture'], $pathImages);
            // On supprime le fichier du post actuel (ex : 'assets/img/haru.jpg')
            unlink($pathImages . $post->getPicture());
            if($successTransfer){
                // Modif de l'image du post
                $post->setPicture($_FILES['picture']['name']);
            }
        }
        // MODIFICATION DE LA BDD , puis message flash et redirection
        $postTable->update($post);
        // Param du message flash de SESSION, puis redirection
        $session->setFlash('success', "Modification réussie !!!!");
        header('Location: ' . $router->url('admin_posts', ['id' => $post->getId()]));
        
    }

    

}
?>

<!-- EDITION D'UN ARTICLE (post)-->
<div class="container">
    <h1 class="text-center mb-4">Edition de l'article : <?= htmlentities($post->getName()) ?></h1>

    <!-- Notification Utilisateur (messages flash) -->
    <?= Notification::toast($messages) ?>
    <!-- FORMULAIRE D'EDITION DE L'ARTICLE (via notre classe Form.php) -->
    <?php require ('_form.php') ?>
</div>