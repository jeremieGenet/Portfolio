<?php
namespace App\Table;

use App\Models\Logo;
use App\Models\Post;
use PDO;

// Gère les requêtes en relation avec la table "logo" (la table des logo)
class LogoTable extends Table{

    // Ces 2 propriétés permettent de donner les infos nécessaires à la class Table.php
    protected $table = "logo"; // Nom de la table dans la bdd
    protected $class = Logo::class; // Class qui défini le mode de recherche dans la bdd


    // Insère une catégorie dans la bdd
    public function insert(Logo $logo)
    {

        $query = $this->pdo->prepare("INSERT INTO {$this->table} SET 
            name = :name,
            size = :size,
            post_id = :post_id
        "); 
        // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item
        $result = $query->execute([ 
            'name' => $logo->getName(),
            'size' => $logo->getSize(),
            'post_id' => $logo->getPost_id()
        ]);
        // Si la création de l'article n'a pas fonctionnée alors...
        if($result === false){
            throw new \Exception("Impossible d'insérer le logo dans la table {$this->table}");
        }
        //dd($post->getId()); // Retourne "null"
        $logo->setId((int)$this->pdo->lastInsertId()); // On récup l'id du logo nouvellement créé (pour l'utiliser comme param de redirection)
        
    }

    // Modifie une catégorie dans la bdd
    public function update(Logo $logo): void
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug WHERE id = :id");
        $result = $query->execute([ // $result vaudra "true" ou "false" en fonction de la réussite ou non de la suppression de l'item
            'id' => $logo->getId(),
            'name' => $logo->getName(),
            'size' => $logo->getSize(),
            'post_id' => $logo->getPost_id()
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