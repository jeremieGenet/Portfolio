<?php
/*
    PAGE D'ACCUEIL DU SITE (one_page)
*/
use App\Session;

$session = new Session();
$title = "Accueil";

?>

<!-- languages -->
<div id="languages" class="languages-section"> <!-- class="testimonials-section" -->
    <div class="container">
        <h2 class="section-title text-center  text-dark">Les<strong> principaux langages </strong>du Web </h2>
        <div class="item mx-auto">
            <div class="profile-holder">
                <img class="profile-image" src="../assets/icons&logos/one_page/logo-html5.svg" alt="HTLM5" alt="profile">
            </div>
            <div class="quote-holder">
                <blockquote class="quote">
                    <h3 class="text-center text-dark">HTML 5</h3>
                    <p>
                        Dernière évolution majeur (Hyper Text Language 5), le HTML est un langage d'organisation, ou de 'balisage'. 
                        Ses balises permettent de donner du sens au contenu d'un site, et sont essentielles au fonctionnement des autres langages Web qui lui sont associés.
                        Sans HTML, une page web n'a aucun sens pour les moteurs de recherche.
                    </p>
                    <div class="quote-source">
                        <span class="meta">La base du Web</span>
                    </div>
                </blockquote>
            </div>
        </div>
        <div class="item item-reversed mx-auto">
            <div class="profile-holder">
                <img class="profile-image" src="../assets/icons&logos/one_page/logo-css3.svg" alt="CSS3">
            </div>
            <div class="quote-holder">
                <blockquote class="quote">
                    <h3 class="text-center text-dark">CSS 3</h3>
                    <p>
                        Feuilles de style en cascade, le CSS (Cascading Style Sheets) est un langage qui décrit la présentation des document HTML ou XML.
                        Il permet de donner du style (couleurs, dimensions, animations, placements...) aux éléments HTLM d'un site et le rend plus agréable
                        à regarder (ou pas).
                    </p>
                    <div class="quote-source">
                        <span class="meta">Le style du Web</span>
                    </div>
                </blockquote>
            </div>
        </div>
        <div class="item mx-auto">
            <div class="profile-holder">
                <img class="profile-image" src="../assets/icons&logos/one_page/javascript_128X128.png" alt="Javascript">
            </div>
            <div class="quote-holder">
                <blockquote class="quote">
                    <h3 class="text-center text-dark">Javascript</h3>
                    <p>
                        Langage de programmation de scripts employé principalement dans les pages web interactives, mais aussi pour les serveurs.
                        Il est orienté objet à prototype et permet de manipuler le HTML très facilement.
                    </p>
                    <div class="quote-source">
                        <span class="meta">L'interactivité du Web</span>
                    </div>
                </blockquote>
            </div>
        </div>
        <div class="item item-reversed mx-auto">
            <div class="profile-holder">
                <img class="profile-image" src="../assets/icons&logos/one_page/PHP_128X128.png" alt="PHP">
            </div>
            <div class="quote-holder">
                <blockquote class="quote">
                    <h3 class="text-center text-dark">PHP</h3>
                    <p>
                        Hyper Preprocessor, ou PHP est un langage de programmation libre orienté objet. Principalement utilisé pour produire des pages Web dynamiques
                        via un serveur HTTP, mais peut fonctionner comme n'importe quel langage interprété de façon locale.
                        PHP est considéré comme une des bases de la création de sites web et applications dynamiques.
                    </p>
                        <div class="quote-source">
                        <span class="meta">Le back-end du Web</span>
                    </div>
                </blockquote>
                
            </div>
        </div>
    </div>
</div><!--//languages-->

<!-- Framwork -->
<div id="frameworks" class="frameworks-section">
    <div class="container">
        <h2 class="section-title text-center text-dark"><strong>Frameworks</strong> et <strong>librairies</strong> populaires dans le web</h2>
        <div class="item mx-auto">
            <div class="profile-holder">
                <img class="profile-image" src="../assets/icons&logos/one_page/logo-bootstrap.svg" alt="Bootstrap">
            </div>
            <div class="quote-holder">
                <blockquote class="quote">
                    <h3 class="text-center text-dark">Bootstrap</h3>
                    <p>
                        Le framework CSS le plus utilisé, crée par et pour Twitter puis devenu open-source. Bootstrap rend le style plus simple à mettre en place, compatible
                        avec tous les navigateurs et ajoute nombre d'outils supplémentaires.
                    </p>
                    <div class="quote-source">
                        <span class="meta">Grand frère du CSS</span>
                    </div>
                </blockquote>
            </div>
        </div>
        <div class="item item-reversed mx-auto">
            <div class="profile-holder">
            <img class="profile-image" src="../assets/icons&logos/one_page/symfony128x128.svg" alt="Symfony">
            </div>
            <div class="quote-holder">
                <blockquote class="quote">
                <h3 class="text-center text-dark">Symfony</h3>
                    <p>
                        Framework libre écrit en PHP, il fournit des fonctionnalités modulables et adaptables pour facilité et accélérer le
                        fonctionnement d'un site web.
                        C'est l'agence française SensioLabs qui est à l'origine du framework pour ses propres besoins, puis rendu libre.
                    </p>
                    <div class="quote-source">
                        <span class="meta">FrameWork PHP français</span>
                    </div>
                </blockquote>
            </div>
        </div>
        <div class="item mx-auto">
            <div class="profile-holder">
            <img class="profile-image" src="../assets/icons&logos/one_page/React.js_128x128.png" alt="REACT">
            </div>
            <div class="quote-holder">
                <blockquote class="quote">
                    <h3 class="text-center text-dark">REACT</h3>
                    <p>
                        Librairie  (ou bibliothèque) libre, écrite en Javascript, développée par Facebook. React a comme principal avantage d'utiliser des composants
                        qui manipulent le HTML mais de façon très ciblé. Ce qui rend les sites, ou applications développée ultra rapide.
                    </p>
                    <div class="quote-source">
                        <span class="meta">Librairie Javascript</span>
                    </div>
                </blockquote>
            </div>
        </div>
        
    </div>
</div><!--//framework-->

<!-- MES SERVICES -->
<div id="services" class="services-section">
    <div class="container text-center">
        <header class="mb-5">
            <h2 class="services-title">MES <strong>SERVICES</strong></h2>
            <p class="services-intro">Des prestations adaptées à vos besoins</p>
        </header>
        <div class="bg-services">
            <div class="row services">
                <!-- Services (gauche) -->
                <div class="col-xs-12 col-sm-6 col-md-5">
                    <div class="row services-icons-texts-1">
                        <div class="col-md-9">
                            <h4 class="services-titles">Gestion & Conception de projets Web</h4>
                            <p>Site vitrine, corporate, évènementiel, <br> e-commerce, intranet, application mobile.</p>
                        </div>
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-keyboard fa-2x"></i>
                        </div>
                    </div>
                    <div class="row services-icons-texts-2">
                        <div class="col-md-9">
                            <h4 class="services-titles">Intégration Web</h4>
                            <p>Des intégrations HTML & CSS <br> qui respectent les standards du Web.</p>
                        </div>
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-laptop-code fa-2x"></i>
                        </div>
                    </div>
                    <div class="row services-icons-texts-1">
                        <div class="col-md-9">
                            <h4 class="services-titles">Développement Spécifiques</h4>
                            <p>Outils adaptés à votre coeur de métier, <br> application & solutions personnalisées.</p>
                        </div>
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-marker fa-2x"></i>
                        </div>
                    </div>
                    <div class="row services-icons-texts-2">
                        <div class="col-md-9">
                            <h4 class="services-titles">Référencement Naturel</h4>
                            <p>Affichage sémantique des informations <br> pour un référencement optimal.</p>
                        </div>
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-sitemap fa-2x"></i>
                        </div>
                    </div>
                </div>

                <!-- Image au centre des Services -->
                <div class="col-xs-12 col-md-2 d-none d-lg-block">
                    <img class="figure-image img-fluid m3" src="../assets/images/one_page/figure-1.png" alt="image" />
                    <img class="figure-image img-fluid m3" src="../assets/images/one_page/figure-2.png" alt="image" />
                    <img class="figure-image img-fluid m3" src="../assets/images/one_page/figure-3.png" alt="image" />
                </div>

                <!-- Services (droite) -->
                <div class="col-xs-12 col-sm-6 col-md-5">
                    <div class="row services-icons-texts-1">
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-drafting-compass fa-2x"></i>
                        </div>
                        <div class="col-md-9">
                            <h4 class="services-titles">Conception Graphique & WebDesign</h4>
                            <p>Logos, templates Web, plaquettes publicitaires,<br> carte de visite, charte graphique...</p>
                        </div>
                    </div>
                    <div class="row services-icons-texts-2">
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-project-diagram fa-2x"></i>
                        </div>
                        <div class="col-md-9">
                            <h4 class="services-titles">Pages Dynamiques</h4>
                            <p>Des animations de contenu non intrusives<br> pour améliorer vos projets.</p>
                        </div>
                    </div>    
                    <div class="row services-icons-texts-1">
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-database fa-2x"></i>
                        </div>
                        <div class="col-md-9">
                            <h4 class="services-titles">Interface d'administration</h4>
                            <p>Outils spécifiques au bon fonctionnement <br>de votre entreprise.</p>
                        </div>
                    </div>
                    <div class="row services-icons-texts-2">
                        <div class="col-md-3 mt-3 d-none d-lg-block">
                            <i class="services-icon fas fa-tablet-alt fa-2x"></i>
                        </div>
                        <div class="col-md-9">
                            <h4 class="services-titles">Responsive Design</h4>
                            <p>Compatible et adapté tous supports,<br> tablettes, mobiles et ordinateurs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--//container-->
</div><!--//Mes services-->

<!-- FORMULAIRE DE DEMANDE -->
<div class="section-demande-devis" id="demande-devis">
    <div class="container text-center">
        <h1>Une idéé ou un projet ? N'hésitez pas à <span>demander un devis !</span> (GRATUIT)</h1>
        <form>
            <fieldset>
                <legend>Legend</legend>
                <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" readonly="" class="form-control-plaintext" id="staticEmail" value="email@example.com">
                </div>
                </div>
                <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="form-group">
                <label for="exampleSelect1">Example select</label>
                <select class="form-control" id="exampleSelect1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
                </div>
                <div class="form-group">
                <label for="exampleSelect2">Example multiple select</label>
                <select multiple="" class="form-control" id="exampleSelect2">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
                </div>
                <div class="form-group">
                <label for="exampleTextarea">Example textarea</label>
                <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                </div>
                <div class="form-group">
                <label for="exampleInputFile">File input</label>
                <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
                </div>
                <fieldset class="form-group">
                <legend>Radio buttons</legend>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                    Option one is this and that—be sure to include why it's great
                    </label>
                </div>
                <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2">
                    Option two can be something else and selecting it will deselect option one
                    </label>
                </div>
                <div class="form-check disabled">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" disabled="">
                    Option three is disabled
                    </label>
                </div>
                </fieldset>
                <fieldset class="form-group">
                <legend>Checkboxes</legend>
                <div class="form-check">
                    <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="" checked="">
                    Option one is this and that—be sure to include why it's great
                    </label>
                </div>
                <div class="form-check disabled">
                    <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" value="" disabled="">
                    Option two is disabled
                    </label>
                </div>
                </fieldset>
                <button type="submit" class="btn btn-primary">Submit</button>
            </fieldset>
        </form>
        
    </div>
</div><!--//team-section-->

<!-- CONTACT -->        
<div id="contact" class="contact-section">
    <div class="container text-center">
        <h2 class="section-title">Contact Us</h2>
        <div class="contact-content">
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis.</p>
            
        </div>
        <a class="btn btn-cta btn-primary" href="https://themes.3rdwavemedia.com/bootstrap-templates/startup/appkit-landing-free-bootstrap-theme-for-developers-and-startups/">Get in Touch</a>
        
    </div><!--//container-->
</div><!--//contact-section-->

