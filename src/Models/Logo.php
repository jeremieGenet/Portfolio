<?php
namespace App\Models;

use App\Models\Post;

class Logo{
    
    private $id;
    private $slug;
    private $size;

    private $post_id; // Correspond au champs de la table post_logo (utile pour les liaisons entre tables)
    private $post; // permet de récup un post avec toute ses catégories

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self // ": self" pour le typage du retour (retourne l'item en cours)
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }
    public function setSize(string $size): self 
    {
        $this->size = $size;
        return $this;
    }

    // Récup l'id d'un post qui appartient au logo (utile pour remplir l'attribut 'catégories[]' de la classe Post.php)
    public function getPostId(): ?int
    {
        return $this->post_id;
    }
    // Fonction qui récup le post (avec ses logo, voir Post.php)
    // Permet de modifier l'article (utilisé dans Post.php via sa méthode addCategories())
    public function setPost(Post $post){
        $this->post = $post;
    }
    

}