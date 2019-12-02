<?php
namespace App\Models;

use \DateTime;
use App\Helpers\Text;

// Représente la table des réalisations
class Post{

    private $id;
    private $picture;
    private $name;
    private $slug;
    private $content;
    private $created_at;
    private $likes;
    private $isLiked;

    private $categories = []; // Tableau qui récup les catégories de la réalisation (LIAISON avec la table blog_post_category)
    private $logo = [];  // liste de logos


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPicture(): ?string
    {
        /*
           Une simple chaine de caractères
        */
        return $this->picture;
    }
    public function setPicture($picture): self
    {
        $this->picture = $picture;
        return $this;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self // ": self" pour le typage du retour (retourne l'item en cours)
    {
        $this->name = $name;
        return $this; // (retourne l'objet en cours)
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function getSlugFormat(): ?string
    {
        // Formatage du nom du post en slug (titre-de-qualité)
        $name = explode(' ', $this->name);
        $slugName = implode("-", $name);
        return strtolower($slugName);
    }
    public function setSlug(string $slug): ?string /////////////////////// J'ai laisser une erreur pour test le return $this, et le return $this->slug //////////////////
    {
        $this->slug = $slug;
        return $this->slug;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
    // Retourne le contenu de l'article mais dans une limite de lettre (voir Text::excerpt())
    public function getContent_excerpt(): ?string
    {
        if($this->content === null){
            return null;
        }
        // "nl2br" permet de respecter les sauts de lignes
        return nl2br(htmlentities(Text::excerpt($this->content, 120)));
    }
    // Retourne le contenu de l'article avec sauts de lignes et sécurisé par htmlentities()
    public function getFormatedContent(): ?string
    {
        return nl2br(htmlentities($this->content));
    }
    public function setContent(string $content): self // ": self" pour le typage du retour (retourne l'item en cours)
    {
        $this->content = $content;
        return $this; // (retourne l'objet en cours)
    }

    public function getCreatedAt(): DateTime
    {
        // retourne la date actuelle (au format "Y-m-d H-i-s", le format MySQL)
        return new DateTime($this->created_at);
    }
    // Retourne la date de création au format FR ('d F Y')
    public function getCreatedAt_fr()
    {
        return $this->getCreatedAt()->format('d F Y');
    }
    // Modifie la date de création (retourne une chaîne de caractères)
    public function setCreatedAt(string $date): self
    {
        $this->created_at = $date;
        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }
    public function setLikes($likes): self
    {
        $this->likes = $likes;
        return $this;
    }

    public function getIsLiked(): ?bool
    {
        return $this->isLiked;
    }
    public function setIsLiked($isLiked): self
    {
        $this->isLiked = $isLiked;
        return $this;
    }
    
    // Récup la liste des catégories (d'un article, post)
    public function getCategories(): array
    {
        return $this->categories;
    }

    // Permet d'ajouter des catégories
    public function setCategories($category): void
    {
        $this->categories[] = $category;
        // On sauvegarde le post associé dans la classe Category.php (pas utile pour notre blog, mais permet dans Category.php de récup le post et ses catégories)
        //$category->setPost($this); 
    }

    // Récup la liste des logos
    public function getLogo(): ?array
    {
        return $this->logo;
    }
    // Permet d'ajouter des logos
    public function setLogo($logo): void
    {
        $this->logo[] = $logo;
    }




}