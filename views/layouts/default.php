
<!-- LAYOUT DU SITE (hors administration) -->

<!DOCTYPE html>
<!-- class "h-100" pour height 100% (et permettre de mettre le footer tout en bas)-->
<html lang="fr" class="h-100"> 
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <!-- Affichera "Mon site" si la variable "$title" n'est pas définie -->
        <title>
            <?= $title ?? 'Mon site' ?> 
        </title>

        <!-- Style Bootstrap (../../css/app.css)-->
        <link rel="stylesheet" href="../../assets/plugins/bootstrap/css/bootstrap.css">
        

    </head>

    <!-- class "h-100" pour height 100% (et permettre de mettre le footer tout en bas) -->
    <body class="d-flex flex-column h-100"> 

        <!-- HEADER DU SITE -->
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
                <a class="navbar-brand" href="<?=  $router->url('home') ?>">Mon Site</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <!-- LIEN VERS LE BLOG -->
                            <a class="nav-link" href="<?=  $router->url('achievements') ?>">Réalisations</a>
                        </li>
                        <li class="nav-item">
                            <!-- LIEN VERS LA GESTION DES ARTICLES (édition/suppréssion) -->
                            <a class="nav-link" href="<?=  $router->url('admin_posts') ?>">Articles</a>
                        </li>
                        <li class="nav-item">
                            <!-- LIEN VERS LA GESTION DES CATEGORIES (édition/suppréssion) -->
                            <a class="nav-link" href="<?=  $router->url('admin_categories') ?>">Catégories</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                            
                        <?php if(isset($_SESSION['user'])): ?>
                            <!-- DECONNEXION (formulaire pour éviter que qqun puisse envoyer le lien et déconnecter l'utilisateur de force) -->
                            <li class="nav-item">
                                <form action="<?= $router->url('logout') ?>" method="POST" class="nav-item">
                                    <button type="submit" class="btn btn-warning">Se déconnecter</button>
                                </form>
                            </li>
                        <?php elseif(empty($_SESSION['user'])): ?>
                            <li class="nav-item">
                                <!-- SE CONNECTER -->
                                <a class="btn btn-light mr-2" href="<?=  $router->url('register') ?>">S'inscrire</a>
                            </li>
                            <li class="nav-item">
                                <!-- SE CONNECTER -->
                                <a class="btn btn-primary" href="<?=  $router->url('login') ?>">Se connecter</a>
                            </li>
                        <?php endif; ?>
                        
                    </ul>
                </div>
            </nav>
            
            <!-- Debug session utilisateur (affichage) -->
            <?php 
                if(isset($_SESSION)){
                    var_dump($_SESSION);
                }
                if(isset($_SESSION['flash'])){
                    var_dump($_SESSION['flash']);
                }
                if(isset($_SESSION['infoUser'])){
                    var_dump($_SESSION['infoUser']);
                }
            ?>
            
        </header>

        

        <!-- CONTENU DU SITE -->
        <div class="container-fluid my-4">

            <?= $content ?>

        </div> <!-- Fin de div container -->    

        <!-- FOOTER DE L'ENSEMBLE DES PAGES DE L'ESPACE MEMBRES -->
        <!-- "mt-auto permet de caller le footer en bas de l'écran (il faut 'h-100' sur le body et le html-->
        <footer class="py-5 text-center mt-auto bg-light"> 
            <p>© 2025 by me. BLOG Proudly created with by my fingers</p>
            <!--Calcul du délai d'affichage de la page (DEBUG_TIME est un timestamp en mili-secondes) -->
            <p>Page générée en <?= round(1000 * (microtime(true) - DEBUG_TIME)) ?>ms</p> 
        </footer>

        <!-- Les 3 scripts suivants servent au fonctionnement de bootstrap -->
        <script src="../../js/jquery.min.js"></script>
        <script src="../../js/popper.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        
        <script src="../../js/custom.js"></script>

    </body>

</html>