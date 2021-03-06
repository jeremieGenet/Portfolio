<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Affichera "Mon site" si la variable "$title" n'est pas définie -->
    <title>
        <?= $title ?? 'Mon site' ?> 
    </title>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rapide aperçu de quelques projets perso, et présentation rapide">
    <meta name="author" content="Jeremie Genet">    

    <!-- Fontawesome -->
    <!--
    <link href="../assets/plugins/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="../assets/plugins/fontawesome/css/brands.css" rel="stylesheet">
    <link href="../assets/plugins/fontawesome/css/solid.css" rel="stylesheet">  
    -->
    <!-- Google fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <!-- FontAwesome JS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" 
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- Css Bootstrap -->
    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.css">   
    <!-- Css One_page -->  
    <link rel="stylesheet" href="../../assets/css/one_page/one_page.css">
    
</head> 

<body>
    <header id="header" class="header">  
        <div class="container">       
            <h1 class="logo">
                <a class="scrollto" href="#hero">
                    <!-- LOGO -->
                    <span class="logo-icon-wrapper"><img class="logo-icon" src="../assets/icons&logos/one_page/logo.svg" alt="logo site"></span>
                </a>
            </h1>
            <nav class="main-nav navbar-expand-md float-right navbar-inverse" role="navigation">
                <!-- TOGGLE NAV -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- NAV BAR-->
                <div id="navbar-collapse" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav mr-5">
                        <li class="nav-item"><a class="active nav-link scrollto" href="#std-web">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link scrollto" href="#languages">Standards</a></li>
                        <!-- <li class="nav-item"><a class="nav-link scrollto" href="#frameworks">FrameWorks</a></li> -->
                        <li class="nav-item"><a class="nav-link scrollto" href="#services">Mes services</a></li>                        
                        <li class="nav-item"><a class="nav-link scrollto" href="#demande-devis">Devis gratuit</a></li>
                        <li class="nav-item"><a class="nav-link scrollto" href="#contact">Contact</a></li>
                </div>
            </nav>                    
        </div>
    </header>
    

    <!-- CAROUSSEL -->
    <div id="hero" class="hero-section">
        <div id="hero-carousel" class="hero-carousel carousel carousel-fade slide" data-ride="carousel" data-interval="10000">
            <div class="figure-holder-wrapper">
        		<div class="container d-none d-lg-block">
            		<div class="row justify-content-end">
                		<div class="figure-holder">
                            <!-- IMAGE IMAC -->
                	        <img class="figure-image img-fluid" src="../assets/images/one_page/imac3.png" alt="image" />
                        </div>
            		</div>
        		</div>
    		</div>
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li class="active" data-slide-to="0" data-target="#hero-carousel"></li>
				<li data-slide-to="1" data-target="#hero-carousel"></li>
				<li data-slide-to="2" data-target="#hero-carousel"></li>
			</ol>
			<!-- Wrapper des items du caroussel -->
			<div class="carousel-inner">
                <!-- ELEMENT 1 caroussel -->
				<div class="carousel-item item-1 active">
					<div class="item-content container">
    					<div class="item-content-inner">
    				        
				            <h2 class="heading">Créer un site ou <br class="d-none d-md-block">une application Web ?</h2>
				            <p class="intro">Vous avez une idée de site ou d'application Web et vous désirez la concrétiser...</p>
                            <a class="btn btn-primary btn-cta scrollto" href="#services" >
                                Voir mes services
                            </a>
    				        
    					</div>
					</div>
				</div>
				<!-- ELEMENT 2 caroussel -->
				<div class="carousel-item item-2">
					<div class="item-content container">
						<div class="item-content-inner">
    				        
				            <h2 class="heading">Votre site ou application Web a besoin d'être modifiée ou rénovée ?</h2>
				            <p class="intro">Ajouter des fonctionnalités, donner un coup de "peinture", améliorer sa visibilité</p>
                            <a class="btn btn-primary btn-cta scrollto" href="#services" >
                                Voir mes services
                            </a>
    				        
    					</div>
					</div>
				</div>
				<!-- ELEMENT 3 caroussel -->
				<div class="carousel-item item-3">
					<div class="item-content container">
						<div class="item-content-inner">
    				        
				            <h2 class="heading">Besoin de conseils sur les technos à employer pour vos projets Web ?</h2>
				            <p class="intro">Déterminer en amont les langages de programmation à utiliser, et surtout les outils qui permettront un développement en raccord avec vos objectif.</p>
                            <a class="btn btn-primary btn-cta scrollto" href="#services">
                                Voir mes services
                            </a>

    					</div>
					</div>
				</div>
			</div>

		</div>
    </div><!-- Fin du Caroussel-->
    
    <!--  STD-WEB   LES STANDARD DU WEB AU SERVICE DE VOS PROJETS -->
    <div id="std-web" class="std-web">
        <div class="container text-center">
            <h2 class="section-title">Les <strong>standards du web</strong> au service de vos projets</h2>
            <p class="intro">Quelques langages/outils qui vous permettront d'atteindre vos objectifs numériques</p>
            <ul class="technologies list-inline">
                <li class="list-inline-item"><img src="../assets/icons&logos/one_page/logo-html5.svg" alt="HTML5"></li>
                <li class="list-inline-item"><img src="../assets/icons&logos/one_page/logo-css3.svg" alt="CSS3"></li>
                <li class="list-inline-item"><img src="../assets/icons&logos/one_page/logo-bootstrap.svg" alt="Bootstrap"></li>
                <li class="list-inline-item"><img src="../assets/icons&logos/one_page/PHP_128x128.png" alt="less"></li>
                <li class="list-inline-item"><img src="../assets/icons&logos/one_page/javascript_128x128.png" alt="less"></li>
            </ul>
            
            <div class="items-wrapper row">
                <div class="item col-md-4 col-12">
                    <div class="item-inner">
                        <div class="figure-holder">
                            <img class="figure-image" src="../assets/images/one_page/figure-1.png" alt="image">
                        </div>
                        <h3 class="item-title">Etude & Réflexion</h3>
                        <div class="item-desc">
                            D'abord réfléchir et analyser le projet pour en déterminer le design et les outils adaptés qui amèneront à la réalisation. 
                        </div>
                    </div>
                </div>
                <div class="item col-md-4 col-12">
                    <div class="item-inner">
                        <div class="figure-holder">
                            <img class="figure-image" src="../assets/images/one_page/figure-2.png" alt="image">
                        </div>
                        <h3 class="item-title">Design & développement</h3>
                        <div class="item-desc">
                            En accord avec le design déterminé, coder l'application avec les outils déterminées.
                        </div>
                    </div>
                </div>
                <div class="item col-md-4 col-12">
                    <div class="item-inner">
                        <div class="figure-holder">
                            <img class="figure-image" src="../assets/images/one_page/figure-3.png" alt="image">
                        </div>
                        <h3 class="item-title">Finition & mise en production</h3>
                        <div class="item-desc">
                            Apporter les finitions et corrections, puis mettre l'application en ligne pour enfin concrétiser le projet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- CONTENU DU SITE -->
    <div class="container-fluid my-4">
        <?= $content ?>
    </div>
    

    <footer class="footer text-center">
        <div class="container">
            <small class="copyright">
                <p>© 2018 - 2020. <strong>Proudly</strong> created with by my fingers <i class="fas fa-heart"></i></p> 
                <p>Page générée en <a href=""><strong><?= round(1000 * (microtime(true) - DEBUG_TIME)) ?></strong> millisecondes</a></p>
            </small>
        </div>
    </footer>
     
    <!-- JAVASCRIPT -->  
    <!-- Jquery v3.3.1 (utile au fonctionnement de bootstrap, et de jquery.scrollTo) -->        
    <script type="text/javascript" src="../assets/plugins/jquery-3.3.1.min.js"></script>
    <!-- Utile au fonctionnement du scroll de jquery.scrollTo.js -->
    <script type="text/javascript" src="../assets/plugins/jquery-scrollTo/jquery.scrollTo.js"></script>
    <!-- Javascript utile au fonctionnement de Bootstrap -->
    <script type="text/javascript" src="../assets/plugins/bootstrap/js/bootstrap.js"></script>
    <!-- Gestion du scroll de la page en jquery -->
    <script type="text/javascript" src="../assets/js/one_page/main.js"></script> 
       
</body>
</html> 

