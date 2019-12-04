<?php
namespace App\Table;

use App\Models\Post;
use App\Helpers\PaginatedQuery;
use App\Models\Category;


// Gère les requêtes de la table "Post" (table des articles)
class PostTable extends Table{

    // Ces 2 propriétés permettent de donner les infos nécessaires à la class Table.php
    protected $table = "post"; // Nom de la table dans la bdd
    protected $class = Post::class; // Class qui défini le mode de recherche dans la bdd

    /*
        METHODES DANS LE MODEL Table.php :

        function find()  
        function findAll()
        function exists()  Vérif si un item existe
    */


    // Insère un post dans la bdd (et insére l'id et post et l'id de la catégorie du post dans la table post_category)
    public function insert(Post $post): void
    {

        //dd($post->getLogoCollection());

        // INSERTION DU POST
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET 
            name = :name,
            picture = :picture,
            slug = :slug, 
            content = :content, 
            created_at = :createdAt,
            likes = :likes,
            isLiked = :isLiked
        "); 
        // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item
        $result = $query->execute([ 
            'name' => $post->getName(),
            'picture' => $post->getPicture(),
            'slug' => $post->getSlugFormat(),
            'content' => $post->getContent(),
            'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'), // Formatage au format admit par MySQL
            'likes' => $post->getLikes(),
            'isLiked' => $post->getIsLiked()
        ]);
        // Si la création de l'article n'a pas fonctionnée alors...
        if($result === false){
            throw new \Exception("Impossible de créer l'article dans la table {$this->table}");
        }
        // On récup l'id du post créé (pour l'utiliser comme param de redirection)
        $post->setId((int)$this->pdo->lastInsertId());

        // INSERTION TABLE LIAISON (post_category)
        // Boucle pour insérer le ou les catégories (reçu par les check-box du formulaire) (un post peu avoir plusieurs catégorie)
        foreach($post->getCategories()[0] as $id){
            // INSERER LA OU LES NOUVELLES CATEGORIES RECUE DANS LA TABLE post_category (besoin post_id et category_id)
            $query2 = $this->pdo->prepare("INSERT INTO post_category SET
            post_id = :post_id,
            category_id = :category_id
            ");
            $result2 = $query2->execute([
                'post_id' => $post->getId(),
                'category_id' => $id
            ]);

            if($result2 === false){
                throw new \Exception("Impossible de modifier la table post_category ! ");
            }
        }

        // INSERTION TABLE LIAISON (post_logo)
        // Boucle pour insérer le ou les logo (collection)
        foreach($post->getLogoCollection() as $logo){
            //dd($logo, $logo->getId(), $post->getId());
            $query2 = $this->pdo->prepare("INSERT INTO post_logo SET
            post_id = :post_id,
            logo_id = :logo_id
            ");
            $result2 = $query2->execute([
                'post_id' => $post->getId(),
                'logo_id' => $logo->getId()
            ]);

            if($result2 === false){
                throw new \Exception("Impossible de modifier la table post_logo ! ");
            }

        }

        
    }
    
    // Modifie un post dans la bdd (et modifie l'id de la catégorie dans la table post_category)
    public function update(Post $post): void
    {
        //dd($post, $post->getCategories());
        //dd($post->getPicture(), $post->getCategories());
        // UPDATE DU POST
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, picture = :picture, slug = :slug, content = :content, created_at = :createdAt WHERE id = :id");
        $result = $query->execute([ // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item
            'id' => $post->getId(),
            'name' => $post->getName(),
            'picture' => $post->getPicture(), // COUILLE j'envoi un array au lieu d'une string avec : $post->getPicture()
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s') // Formatage au format admit par MySQL
        ]); 
        //dd($result);
        // Si la Modification n'a pas fonctionnée alors...
        if($result === false){
            throw new \Exception("Impossible de modifier l'enregistrement $post->getId() dans la table {$this->table}");
        }

        // Si la propriété 'categories' n'est pas vide... (on fait l'update de la table de liaison post_category)
        if($post->getCategories() !== []){

            // SUPPRESSION PUIS INSERTION DE LA OU LES CATEGORIES DANS LA BDD (liaison post_category)
            $query = $this->pdo->prepare("DELETE FROM post_category WHERE post_id = ?");
            // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item (permet de jetter une exception plus bas)
            $result = $query->execute([$post->getId()]);

            // BOUCLE POUR INCLURE LE OU LES ID DES CATEGORIES (reçu par les check-box du formulaire) (un post peu avoir plusieurs catégorie)
            foreach($post->getCategories()[0] as $id){
                // INSERER LA OU LES NOUVELLES CATEGORIES RECUE DANS LA TABLE post_category (besoin post_id et category_id)
                $query2 = $this->pdo->prepare("INSERT INTO post_category SET
                post_id = :post_id,
                category_id = :category_id
                ");
                $result2 = $query2->execute([
                    'post_id' => $post->getId(),
                    'category_id' => $id
                ]);
                if($result2 === false){
                    throw new \Exception("Impossible de modifier la table post_category ! ");
                }
            }

        }
        
    }

    // Supprime un post en fonction de son id (renvoie une exception si cela n'a pas fonctionné)
    public function delete(Post $post, int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item (permet de jetter une exception plus bas)
        $result = $query->execute([$id]); 
        // SUPPRESSION de l'image dans le dossier de stockage (si il y en a une)
        if($post->getPicture()){
            //dd($post->getPicture());
            unlink('assets/upload/img/' . $post->getPicture());
        }
        // Si la suppression n'a pas fonctionnée alors...
        if($result === false){
            throw new \Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
        }


        // Suppression de la JOINTURE dans post_category
        $query2 = $this->pdo->prepare("DELETE FROM post_category WHERE post_id = ?");
        $result2 = $query2->execute([$id]);
        if($result2 === false){
        throw new \Exception("Impossible de supprimer l'enregistrement $id dans la table 'post_category' ! ");
        }
        
        
    }

    // Récup les résultats paginés des posts (utilisé pour l'affichage de l'ensemble des articles dans post/index.php)
    public function findPaginated(int $nbElementsPerPage=4)
    {
        /**
         * Instanciation de notre Classe PaginatedQuery()
         * 
         * Param 1 : Requête qui permet de récup les items (articles ici)
         * Param 2 : Requête qui compte les items
         * Param 3 : connection à la bdd
         * Param 4 : le nb d'élément par page (pour la paginationn), 8 par défaut
         */
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC", 
            "SELECT COUNT(id) FROM {$this->table}",
            $this->pdo,
            $nbElementsPerPage
        );
        // Récup des articles (en param la classe sur laquelle on veut récup les items)
        $posts = $paginatedQuery->getItems(Post::class);
        // Rempli l'attribut "categories[]" (par jointure) des posts via la méthode "hydratePost()" de la classe CategoryTable
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        // Retourne la liste des articles et la liste des articles paginés
        return [$posts, $paginatedQuery];
    }

    // Récup les résultats paginés des posts (utilisé pour l'affichage de l'ensemble des articles qui appartiennent à la catégorie selectionnée dans category/show.php)
    public function findPaginatedForCategory(int $categoryId, int $nbElementsPerPage=4)
    {
        /**
         * Instanciation de notre Classe PaginatedQuery()
         * 
         * Param 1 : Requête qui permet de récup les items (catégories ici)
         * Param 2 : Requête qui compte les items
         * Param 3 et 4 optionels (inutiles ici)
         */
        $paginatedQuery = new PaginatedQuery(
            "SELECT * 
            FROM {$this->table} bp
            JOIN post_category bpc ON bpc.post_id = bp.id
            WHERE bpc.category_id = {$categoryId}
            ORDER BY created_at DESC",
            "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryId}",
            $this->pdo,
            $nbElementsPerPage
        );
        // Récup des articles (en param la classe sur laquelle on veut récup les items)
        $posts = $paginatedQuery->getItems(Post::class);
        // Rempli l'attribut "categories[]" (par jointure) des posts via la méthode "hydratePost()" de la classe CategoryTable
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        // Retourne la liste des articles et la liste des articles paginés
        return [$posts, $paginatedQuery];
    }

    // Récup des catégories de l'article (via l'id de l'article)
    public function findCategories(int $idPost)
    {
        $query = $this->pdo->prepare('
        SELECT category.id, category.name, category.slug
        FROM post_category pc 
        JOIN category ON pc.category_id = category.id 
        WHERE pc.post_id = :id');
        $query->execute(['id' => $idPost]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Category::class); // On change le mode de recherche (Fetch)
        
        return $query->fetchAll();
    }

}