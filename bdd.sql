/******************** BLOG *****************************/

/* TABLE DES POST DU BLOG */
CREATE TABLE post(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    picture VARCHAR(255) DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    content TEXT(650000) NOT NULL,
    created_at DATETIME NOT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    likes INT DEFAULT NULL,
    isLiked BOOLEAN NOT NULL,
    PRIMARY KEY (id)
)

/* TABLE DES CATEGORIE DU BLOG */
CREATE TABLE category(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)

/* TABLE DES LOGO DU BLOG */
CREATE TABLE logo(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    size INT NOT NULL,
    PRIMARY KEY (id)
)

/* TABLE QUI SERT DE LIEN ENTRE "post" et "category" */
CREATE TABLE post_category(
    post_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, category_id),
    CONSTRAINT fk_post
        FOREIGN KEY (post_id) 
        REFERENCES post (id) /* On donne la table à lier et le nom du champs */
        ON DELETE CASCADE /* Permet de supprimer la ligne entière (le champs de la table) */
        ON UPDATE RESTRICT,/**/
    CONSTRAINT fk_category
        FOREIGN KEY (category_id)
        REFERENCES category (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)

/* TABLE QUI SERT DE LIEN ENTRE "post" et "logo" */
CREATE TABLE post_logo(
    post_id INT UNSIGNED NOT NULL,
    logo_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, logo_id)
)

/******************** ESPACE MEMBRE **************************/

/* TABLE UTILISATEUR */
CREATE TABLE user(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)