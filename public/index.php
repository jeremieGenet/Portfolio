<?php
use App\Router;

require'../vendor/autoload.php';

// Contante qui défini le timestamp avec les micro-secondes (pour le calcul du temps de génération d'une page dans le footer)
define('DEBUG_TIME', microtime(true));

// Utilisation de la librairie Whoops (aide à l'affichage et débug des erreurs)
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Redirection générique pour toute url que possède un param "?page=1" vers la même url sans ce param
if(isset($_GET['page']) && $_GET['page'] === '1'){
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);
    if(!empty($query)){
        $uri = $uri . '?' . $query;
    }
    http_response_code(301);
    header('Location: ' . $uri);
    exit();
}

$router = new Router(dirname(__DIR__) . '/views');

// ACCUEIL DU SITE (one_page)
$router->get('/', 'home.php', 'home'); // Direction vers la page d'accueil du site (one_page)

/*
    REALISATIONS
*/
$router->match('/realisations', 'realisations/achievement/index.php', 'achievements');
$router->match('/intro/realisations', 'realisations/achievement/intro.php', 'intro_achievements');
$router->get('/realisations/category/[*:slug]-[i:id]', 'realisations/category/show.php', 'achievements-category'); // Direction vers la page des réalisations de la catégorie selectionnée
$router->get('/realisations/[*:slug]-[i:id]', 'realisations/achievement/show.php', 'achievement'); // Direction vers la vue d'une réalisation

// CONNEXION/AUTHENTIFICATION
$router->match('/auth/login', 'auth/login.php', 'login');
$router->match('/auth/logout', 'auth/logout.php', 'logout');
$router->match('/auth/register', 'auth/register.php', 'register');
$router->match('/auth/account', 'auth/account.php', 'account');

/*
    ADMINISTRATION (accueil)
*/
$router->get('/admin', 'admin/home_admin.php', 'admin_home'); // Direction l'accueil de l'administration
/*
    ADMINISTRATION
Gestion des articles
*/
$router->get('/admin/post', 'admin/post/index.php', 'admin_posts'); // Direction vers l'administration des articles
$router->match('/admin/post/[i:id]', 'admin/post/edit.php', 'admin_post'); // Direction vers l'administration Modification d'un article ("match" pour pourvoir router en get et en post)
// suppression articles en "post" pour le rooting, afin que l'url ne fonctionne que si on post un formulaire (SECURITE POUR LES REDUCTION D'URL)
$router->post('/admin/post/[i:id]/delete', 'admin/post/delete.php', 'admin_post_delete'); // Direction vers l'administration Supprimer d'un article 
$router->match('/admin/post/new', 'admin/post/new.php', 'admin_post_new'); // Direction vers l'administration Création d'un articles (match pour y accéder en "get" et en "post")

$router->match('/admin/post/[i:postId]/logo/[i:logoId]/delete', 'admin/post/deleteLogo.php', 'admin_post_logo_delete'); // Direction vers l'administration Supprimer d'un logo d'un article
/*
    ADMINISTRATION
Gestion des categories
*/
$router->get('/admin/categories', 'admin/category/index.php', 'admin_categories'); // Direction vers l'administration des catégories
$router->match('/admin/category/[i:id]', 'admin/category/edit.php', 'admin_category'); // Direction vers l'administration Modification d'un article ("match" pour pourvoir router en get et en post)
// suppression articles en "post" pour le rooting, afin que l'url ne fonctionne que si on post un formulaire (SECURITE POUR LES REDUCTION D'URL)
$router->post('/admin/category/[i:id]/delete', 'admin/category/delete.php', 'admin_category_delete'); // Direction vers l'administration Supprimer d'un article 
$router->match('/admin/category/new', 'admin/category/new.php', 'admin_category_new'); // Direction vers l'administration Création d'un articles (match pour y accéder en "get" et en "post")


$router->run();







