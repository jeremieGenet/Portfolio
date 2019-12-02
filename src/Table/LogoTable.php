<?php
namespace App\Table;

use App\Models\Logo;
use PDO;

// Gère les requêtes en relation avec la table "logo" (la table des logo)
class LogoTable extends Table{

    // Ces 2 propriétés permettent de donner les infos nécessaires à la méthode find() de la class Table.php
    protected $table = "logo"; // Table de la bdd (qui permet de trouver un article, voir class Table.php)
    protected $class = Logo::class; // Class qui défini le mode de recherche dans la bdd (voir class Table.php)


    /**
     * Rempli l'attribut "logo[]" (par jointure) des posts
     *
     * @param App\Models\Post[] $posts
     * @return void
     */
    public function hydratePosts(array $posts): void
    {
        // Récup dans le tableau "$postsbyId" des posts (mais on indexe les posts par leur propre id)
        $postsById = [];
        foreach($posts as $post){
            $postsById[$post->getId()] = $post;
        }

        // Récup des catégories. Récup de l'ensemble des champs de la table logo + le champ "post_id" de la table post_logo (JOINTURE)
        // (Catégories qui ont un logo_id similaire à un des id passés dans le tableau "$postsById")
        $logos = $this->pdo->query(
            'SELECT bc.*, bpc.post_id
            FROM post_logo bpc
            JOIN logo bc ON bc.id = bpc.logo_id
            WHERE bpc.post_id IN (' . implode(',', array_keys($postsById)) . ')' // array_keys() pour ne rechercher que sur des entiers (les index du tableau) sinon Erreur
        )->fetchAll(PDO::FETCH_CLASS, Logo::class);
        // On rempli (l'attribut "logo[]") des posts
        foreach($logos as $logo){
            // On push dans le tableau "logo[]" (propriété de jointure de la classe Logo.php) via la méthode setLogo() (méthode de Model/Post.php) ... 
            // ... les posts qui ont un id qui correspondent au logo (posts de la page, indexés par leur propre id)
            $postsById[$logo->getPostId()]->setLogo($logo);
        }
    }

    // Récup les id et noms de la table logo (tableau associatif avec pour index l'id de la catégorie et pour valeur son nom) EXCELLENT
    public function findById()
    {
        $query = $this->pdo->query('
            SELECT id, name FROM ' . $this->table
        );
        $query->setFetchMode(\PDO::FETCH_KEY_PAIR);
        return $query->fetchAll(); // RETOURNE : ["1" => 'JeuxVideo', "3" => 'Console de jeux', "4" => "goodies",...]
    }

    // Insère une catégorie dans la bdd
    public function insert(Logo $logo)
    {
        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET 
        name = :name,
        slug = :slug
        "); 
        // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item
        $result = $query->execute([ 
            'name' => $logo->getName(),
            'slug' => $logo->getSlug()
        ]);
        // Si la création de l'article n'a pas fonctionnée alors...
        if($result === false){
            throw new \Exception("Impossible de créer l'article dans la table {$this->table}");
        }
        //dd($post->getId()); // Retourne "null"
        $logo->setId((int)$this->pdo->lastInsertId()); // On récup l'id de la catégorie créée (pour l'utiliser comme param de redirection)
        
    }

    // Modifie une catégorie dans la bdd
    public function update(Logo $logo): void
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug WHERE id = :id");
        $result = $query->execute([ // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item
            'id' => $logo->getId(),
            'name' => $logo->getName(),
            'slug' => $logo->getSlug(),
            
        ]); 
        // Si la Modification n'a pas fonctionnée alors...
        if($result === false){
            throw new \Exception("Impossible de modifier l'enregistrement $logo->getId() dans la table {$this->table}");
        }
    }

    // Supprime un logo en fonction de son id (renvoie une exception si cela n'a pas fonctionné)
    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");

        // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item (permet de jetter une exception plus bas)
        $result = $query->execute([$id]); 
        
        // Si la suppression n'a pas fonctionnée alors...
        if($result === false){
            throw new \Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
        }
    }


}