<?php
namespace App;

use App\Connection;
use App\Table\LogoTable;
use App\Table\PostTable;


// Permet de gérer une collection d'images (vérif, upload, suppression)
class FilesManager{

    
    private $data;        // Données réçue à la construction (Collection de fichiers, nos logos ici)
    private $errors = []; // Tableau qui recevra les erreur s'il y en a
    private $postTable;   // Permet entre autre de vérif si un champ existe 
    private $logoTable; 
    //private $postId;

    public function __construct($data)
    {
        $this->data = $data;
        $this->pdo = Connection::getPDO();
        $this->postTable = new PostTable($this->pdo);
        $this->logoTable = new LogoTable($this->pdo);
    }
    

    
    // Permet de valider le ou les fichiers reçus, en param le nom du champs à valider (retourne un tableau d'erreurs, ou un tableau vide s'il y en a pas)
    public function valid($fieldName, bool $exist = false): array
    {

        //dd($this->data);
        // VERIF D'UNE IMAGE ('picture', notre photo principale)
        if(is_string($this->data[$fieldName]['name'])){

            $name     = $this->data[$fieldName]['name']; // [ai.png, ps.jpg, ...]
            $size     = $this->data[$fieldName]['size'];

            //dd($error, $this->data[$fieldName]);
            // Vérif si le champ est vide
            if(empty($name)){
                $this->errors[$fieldName] = "Sélectionner un fichier, l'image ne peut être vide !";
            }

            /*
            // Vérif de l'existance d'un champ ($exist est un param de la méthode, qui vaut false par défaut)
            if($exist === true){
                // Méthode exist() permet de vérif dans la bdd si un champ est déja présent (voir dans Table.php)
                // exists() param 1 = Nom du champ, param2 = valeur du nom du champ, param3 = id du post actuel en traitement (param optionnel)
                if($this->postTable->exists($fieldName, $name)){
                    $this->errors['essai'] = "le fichier '{$name}' existe déjà !";
                }
            }
            */
            
            // Récup de l' extension du fichier reçu
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if($ext){
                // Validation des extensions des fichiers
                if(!in_array($ext, array('jpg','jpeg','png','gif'))){
                    $this->errors[$fieldName] = "L'extension du fichier '{$name}' n'est pas valide.";
                }
            }
            
            // Validation de la taille des fichiers
            if($size/1024/1024 > 2){ // Limitation à 2Mo  (1ko = 1024 octets)
                $this->errors[$fieldName] = "Le fichier '{$name}' dépasse la taille autorisée.";
            }
            return $this->errors;

        }

        // VERIF D'UNE COLLECTION D'IMAGE (Nos logos)
        if(is_array($this->data[$fieldName]['name'])){

            $names    = $this->data[$fieldName]['name']; // [ai.png, ps.jpg, ...]
            $sizes     = $this->data[$fieldName]['size'];

            
            foreach($names as $name){
                // Récup des extension les fichiers reçus à partir du nom du fichier
                $exts[] = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                /*
                // Vérif si un nom de fichier existe déja
                if($this->logoTable->existsLogo($name)){
                    $this->errors['logo-collection'] = "le fichier '{$name}' existe déjà !";
                }       
                */

            }
  
            // Validation des extensions des fichiers
            //dd($exts);
            foreach($exts as $ext){
                if(!in_array($ext, array('jpg','jpeg','png','gif'))){
                    $this->errors[$fieldName] = 'L\'extension d\'un ou plusieurs fichier(s) n\'est pas valide.';
                }
            }
            // Validation de la taille des fichiers
            foreach($sizes as $size){
                // Limitation à 2Mo  (1ko = 1024 octets)
                if($size/1024/1024 > 2){
                    $this->errors[$fieldName] = 'Le fichier dépasse la taille autorisée.';
                }
            }
            return $this->errors;

        }

        return $this->errors; // Retournera un tableau ([]) vide s'il n'y a pas d'erreur


    }


    // Upload un fichier de type 'file' ($data) dans un dossier ($path), et le renomme s'il existe déjà, puis retourne le nom du fichier traité (renommé ou pas)
    public function transfer($fieldName, string $path, int $currentPostId = null) // '$currentPostId' pour l'id du post en cours
    {
        // Données du fichier reçu
        $data = $this->data[$fieldName];
        // UPLOAD DES IMAGES RECUES
        // Si c'est une image seule (pour notre image principale) alors on upload le fichier
        if(is_string($data['name'])){

            $file = $data['name']; // vimeo.png

            $name = pathinfo($file, PATHINFO_FILENAME); // nom du fichier sans son extension (vimeo)
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // extension du fichier (png)

            // Si l'image à uploader existe déjà dans la bdd ('exists()' retourne true si un fichier existe dans la bdd)
            if($this->postTable->exists($fieldName, $file)){ 
                // Condition si le param de 'transfer()' est défini (c'est que nous sommes en Edition, donc on ajoute l'id du post actuel)
                if($currentPostId !== null){
                    // On renomme le fichier avec des parenthèses et l'id du post actuel (édition)
                    $newfile = $name .'('. $currentPostId . ')' . '.'. $ext; // ex : image(12).jpg
                    $file = $newfile;
                // Sinon si le param de "transfer()" n'est pas défini (c'est que nous sommes en Création, donc on ajoute l'id du post qui va être créé)
                }else{
                    // On récup l'id du post qui va être créé (création)
                    $nextPostId = $this->postTable->getNextId();
                    // On renomme le fichier avec des parenthèses et l'id du post en cours de création (création)
                    $newfile = $name .'('. $nextPostId . ')' . '.'. $ext; // ex : image(12).jpg
                    $file = $newfile;
                }
            }
            if($file){
                // UPLOAD des images reçues (stockage et si modification de l'image du post, alors suppression de l'image dans le dossier)
                copy( // Copy le nom du fichier "ackechi.png" dans le dossier "img/persona" (copy(source, destination))
                    $data['tmp_name'], // d:\Code\xampp\tmp\phpF319.tmp (chemin du fichier enregistré en le dossier tmp du serveur, qui crypte le nom)
                    $path . $file // direction ou le fichier est envoyé
                );
                
            }
            
            return $file;
        }
        

        // Si c'est une collection d'images (pour les logos) alors on upload les fichiers
        if(is_array($data['name'])){
            
            $newData = []; // Contiendra les données (nom et size des fichiers) après vérif si le nom du fichier exist

            // Compte total des fichiers
            $countfiles = count($data['name']);
            
            // Boucle sur tous les fichiers
            for($i=0; $i<$countfiles; $i++){

                $file = $data['name'][$i];

                $name = pathinfo($file, PATHINFO_FILENAME); // nom du fichier sans son extension (vimeo)
                $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // extension du fichier (png)
                $size = $data['size'][$i];

                // Vérif si un nom de fichier existe déja
                if($this->logoTable->existsLogo($file)){
                    // Condition si le param de 'transfer()' est défini (c'est que nous sommes en Edition, donc on ajoute l'id du post actuel)
                    if($currentPostId !== null){
                        // On renomme le fichier avec des parenthèses et l'id du post actuel (édition)
                        $newfile = $name .'('. $currentPostId . ')' . '.'. $ext; // ex : image(12).jpg
                        $file = $newfile;
                    // Sinon si le param de "transfer()" n'est pas défini (c'est que nous sommes en Création, donc on ajoute l'id du post qui va être créé)
                    }else{
                        // On récup l'id du post qui va être créé
                        $nextPostId = $this->postTable->getNextId() - 1;
                        // On renomme le fichier avec des parenthèses et l'id du post
                        $newfile = $name .'('. $nextPostId . ')' . '.'. $ext; // ex : image(12).jpg
                        $file = $newfile;
                    }
                }
                // Upload des fichiers (move_uploaded_file() permet de déplacer un fichier param1 = nom du fichier à déplacer, param2= direction)
                move_uploaded_file($data['tmp_name'][$i], $path . $file);
                // On rempli notre tableau '$newDate' avec le nom et taille des fichiers
                $newData['name'][$i] = $file; 
                $newData['size'][$i] = $size;
                
            }

            return $newData; // Retourne le tableau avec les données traitées (le nom s'il a été renommé)
        }

        return false;
        
    }

    // Supprime un fichier du dossier
    public function remove($fileName, string $path)
    {
        // 'file_exists()' vérifie si un fichier ou un dossier existe (param = Chemin vers le fichier ou le dossier)
        if(file_exists($path . $fileName)){
            unlink($path . $fileName); // 'unlink' efface un fichier (param = Chemin vers le fichier)
        }

    }




}