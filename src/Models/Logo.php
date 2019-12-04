<?php
namespace App\Models;


class Logo{
    
    private $id;
    private $name;
    private $size;


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

    

}