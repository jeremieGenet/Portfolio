<?php
namespace App\File;


// Permet de gérer une collection d'images (vérif et upload)
class FilesManager{

    private $data;        // Données réçue à la construction (Collection de fichiers, nos logos ici)
    private $errors = []; // Tableau qui recevra les erreur s'il y en a

    public function __construct($data)
    {
        $this->data = $data;
    }

    
    // Vérifie les fichiers reçus, puis les télécharge dans le dossier (passé en param)
    // Retourne un tableau d'erreur ou true (si la vérif et l'upload se sont bien passées)
    public function filesValidTransfer(string $pathFile) // filesValidTransfer() signature exemple = filesValidTransfer('assets/uploads/logo')
    {
        dd($this->data);
        /*
        array:2 [▼
            "picture" => array:5 [▼
                "name" => "vimeo.png"
                "type" => "image/png"
                "tmp_name" => "D:\Code\xampp\tmp\php48FB.tmp"
                "error" => 0
                "size" => 2350
            ]
            "logo-collection" => array:5 [▼
                "name" => array:2 [▼
                0 => "ic_ai.png"
                1 => "ic_ps.png"
                ]
                "type" => array:2 [▶]
                "tmp_name" => array:2 [▶]
                "error" => array:2 [▶]
                "size" => array:2 [▶]
            ]
        ]
        */

        // Tableaux des différents "attributs" (name, tmpName, error, size) des données reçues ($_FILES['logoCollection'])
        $names    = $this->data['name']; // [ai.png, ps.jpg, ...]
        $tmpNames  = $this->data['tmp_name'];
        $errorFiles    = $this->data['error'];
        //dd($errorFiles);
        $sizes     = $this->data['size'];

        foreach($names as $name){
            // Récup des extension les fichiers reçus à partir du nom du fichier
            $exts[] = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        }
    
        $valid = true;

        // Validation des extensions des fichiers
        //dd($exts);
        foreach($exts as $ext){
            if(!in_array($ext, array('jpg','jpeg','png','gif'))){
                $valid = false;
                return $this->errors['logo'] = 'L\'extension d\'un ou plusieurs fichier(s) n\'est pas valide.';
            }
        }
        // Validation de la taille des fichiers
        foreach($sizes as $size){
            // Limitation à 2Mo  (1ko = 1024 octets)
            if($size/1024/1024 > 2){
                $valid = false;
                return $this->errors['logo'] = 'Le fichier dépasse la taille autorisée.';
            }
        }

        foreach($errorFiles as $errorFile){
    
            switch ($errorFile) {

                // Cas ou il n'y a pas d'erreur d'upload des fichier
                case UPLOAD_ERR_OK:
                    // Upload des fichiers dans le dossier 'uploads'
                    if($valid){
                        // Compte total des fichiers
                        $countfiles = count($this->data['name']);
                        // Boucle sur tous les fichiers
                        for($i=0; $i<$countfiles; $i++){

                            $file = $this->data['name'][$i];

                            /*
                            // En cours de dev (vérif si un fichier est déja présent, puis le renomme)

                            $filesInFolder = scandir($pathFile);
                            
                            //dd($filesInFolder, $file);
                            //$result = array_search($file, $filesInFolder);
                            $result = array_search($file, $filesInFolder); // Retourne false si aucun fichier n'a le même nom, sinon retourne l'index du fichier
                            //dd($result);
                            // Si le fichier est dans le dossier ...
                            if($result !== false){
                                // Si le fichier possède une parenthèse avant son extension (test(2).jpg, alors on incrémente le numéro entre parenthèse)

                                // Récup du nom du fichier sans son extension (pour "test.jpg" on récup "test")
                                $n_file = explode('.', $file);
                                $filename = $n_file[0]; 


                                $extFile = $n_file[1];
                                $nb = 1;
                                $add = '(' . $nb . ').';
                                //dd($filename);
                                $newFile = $filename . $add . $extFile; // test(1).jpg

                                //dd($newFile);
                                $file = $newFile;
                            }
                            */

                            // Upload des fichiers (move_uploaded_file() permet de déplacer un fichier param1 = nom du fichier à déplacer, param2= direction)
                            move_uploaded_file($this->data['tmp_name'][$i], $pathFile . DIRECTORY_SEPARATOR . $file); 
                        }
                    }
                break;

                case UPLOAD_ERR_INI_SIZE:
                    $this->errors = 'La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini. ';
                break;

                case UPLOAD_ERR_FORM_SIZE:
                    $this->errors = 'La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire HTML. ';
                break;

                case UPLOAD_ERR_PARTIAL:
                    $this->errors = 'Le fichier n\'a été que partiellement téléchargé. ';
                break;
                
                case UPLOAD_ERR_NO_FILE:
                    $this->errors = 'Aucun fichier n\'a été téléchargé . ';
                break;

                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->errors = 'Un dossier temporaire est manquant.';
                break;

                case UPLOAD_ERR_CANT_WRITE:
                    $this->errors = 'Échec de l\'écriture du fichier sur le disque.';
                break;

                case UPLOAD_ERR_EXTENSION:
                    $this->errors = 'Une extension PHP a arrêté l\'envoi de fichier. PHP ne propose aucun moyen de déterminer quelle extension est en cause. L\'examen du phpinfo() peut aider.';
                break;

                default:
                    $this->errors = 'erreur inconnue';
                break;
            }

            $this->getErrors();
        }

        return true;
              
    }

    // Retourne les erreurs sous forme de tableau
    public function getErrors():array
    {
        if(isset($this->errors)){
            return $this->errors;
        }
        return true;
    }


}