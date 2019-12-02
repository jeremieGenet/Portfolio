<?php
/* 
    PAGE DE CREATION D'UN ARTILCE (post)
*/
use App\Models\Post;
use App\FileTransfer;
use App\Models\Category;
use App\Table\PostTable;
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
$errors = [];

// Si le formulaire est validé...
if(!empty($_POST)){

    $post->setName($_POST['name']); // pour que le nom du post reste en cas d'erreurs
    $post->setContent($_POST['content']); // pour que le contenu du post reste en cas d'erreurs
    $post->setPicture($_FILES['picture']['name']); // pour que l'image du post reste en cas d'erreurs

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
        "logo" => [
            "name" => "logo.jpg",
            "type" => "image/jpeg",
            "tmp_name" => "D:\Code\xampp\tmp\phpBEE6.tmp",
            "error" => 0,
            "size" => 12421
        ]
        "logo1" => [
            "name" => "Ai.jpg",
            "type" => "image/jpeg",
            "tmp_name" => "D:\Code\xampp\tmp\phpBEE6.tmp",
            "error" => 0,
            "size" => 156378
        ]
    ];
    */
    //dd($data);

    /***************************************************************************************************************************************************/
    // 1. Je veux récup les 'name' des chacun des logo (logo, logo1, logo2...) dans un tableau ['logo.jpg', 'ai.png', 'ia.jpg',...]
    // 2. 

    
    // VERIFICATION DES DONNEES
    $validate = new PostValidator($data, $post->getId());

    $errors = $validate->fieldEmpty(['name', 'content', 'category']);
    $errors = $validate->fieldFileEmpty(['picture']);
    $errors = $validate->fieldLength(3, 150, ['name']);
    $errors = $validate->fieldLength(5, 2000, ['content']);
    $errors = $validate->fieldExist(['name']);
    $errors = $validate->fileExist(['picture']);
    $errors = $validate->fileSize(['picture']);
    $errors = $validate->fileExtension(['picture']);

    //dd($errors['category']);

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
        $successTransfer = FileTransfer::transfer($_FILES['picture'], 'assets/img/');
        if($successTransfer){
            // Modif de l'image du post
            $post->setPicture($_FILES['picture']['name']);
        }
        // ENREGISTREMENT DES DONNEES DANS LA BDD
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