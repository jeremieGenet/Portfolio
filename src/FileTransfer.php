<?php
namespace App;

class FileTransfer{

    // Transfert un fichier de type 'file' ($data) dans un dossier ($path) et retourne un booléan
    public static function transfer(array $data, string $path): bool
    {
        // TRAITEMENT DU STOCKAGE DES IMAGES RECUES
        // Si le fichier posté respecte les conditions php de $_FILES (retourne "error" => 4 si le fichier est vide (non posté) par exemple) alors...
        if ($data['error'] === UPLOAD_ERR_OK) { // UPLOAD_ERR_OK = constante php qui vaut 0

            $newFile = $data['name']; // Nom du fichier reçu dans le formulaire de modif (ex : "haru.jpg")
            
            if($newFile){
                // SYSTEME DE STOCKAGE & GESTION DES IMAGES RECUE (stockage et si modification de l'image du post, alors suppression de l'image dans le dossier)
                $retour = copy( // Copy le nom du fichier "ackechi.png" dans le dossier "img/persona" (copy(source, destination))
                    $data['tmp_name'], // d:\Code\xampp\tmp\phpF319.tmp (chemin du fichier enregistré en le dossier tmp du serveur, qui crypte le nom)
                    $path . $newFile // direction ou le fichier est envoyé
                );
                if($retour){
                    return true;
                }
            }

        }
        return false;
    }

}