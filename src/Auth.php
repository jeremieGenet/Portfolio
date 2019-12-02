<?php
namespace App;

use App\Security\ForbiddenException;

class Auth{

    // Vérifie si l'utilisateur est connécté (s'il n'y est pas envoie d'une exception)
    public static function check()
    {
        
        // Si le statut de session est null (session_start() non actif) alors on démarre la session
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        

        // S'il n'y a pas dans "$_SESSION" un 'user' ("user" est inclu dans $_SESSION dans la page login.php lors de la connexion)
        if(!isset($_SESSION['user'])){
            throw new ForbiddenException(); // (on jette une Exception)
        }

        
    }

}