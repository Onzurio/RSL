<?php
require_once('libs/config.php');

class Controleur {
    public function gererRequete() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'accueil';

        switch ($action) {
            case 'signup':
                $this->afficherPageInscription();
                break;
            case 'login':
                $this->afficherPageConnexion();
                break;
            case 'logout':
                 $this->deconnexion();
                break;
            case 'creerCompte':
                $this->creerCompte();
                break;
            case 'creerLocation':
                $this->creerLocation();
                break;
            case 'Connexion':
                $this->connexion();
                break;
            case 'Location':
                $this->afficherPageLocation();
                break;
            default:
                $this->afficherPageAccueil();
                break;
        }
    }

    public function afficherPageAccueil() {
       
        /////////pour le debug
        $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

        // Afficher l'ID de l'utilisateur s'il existe
        if ($userId) {
            echo "Utilisateur ID : $userId<br>";
        }
        ///////////////

        $annonces = getAnnonces();
        if ($userId) {
            // Utilisateur connecté
           
    
            require_once('vue/vue_connecté.php');
            $vue = new VueConnecté();
            $vue->afficherPageAccueilConnecté($annonces);
        } else {
            // Utilisateur non connecté
            require_once('vue/vue.php');
            $vue = new Vue();
            $vue->afficherPageAccueil($annonces);
        }
    }

    public function afficherPageInscription() {
        require_once('vue/signup.php');
        $vue = new VueSignup();
        $vue->afficherPageInscription();
    }

    public function afficherPageConnexion() {
        require_once('vue/login.php');
        $vue = new VueLogin();
        $vue->afficherPageConnexion();
    }

    public function afficherPageLocation() {
        // Récupérer les locations
        $tags = getTags();
        
        // Charger la vue
        require_once('vue/location.php');
        $vue = new VueLocation();
        $vue->afficherPageLocation($tags);
    }

    public function creerCompte() {
        // Récupérer les données du formulaire d'inscription
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // Vérifier si le compte existe déjà
        if (compteExiste($username)) {
            // Gérer l'erreur (par exemple, afficher un message d'erreur)
            require_once('vue/signup.php');
            $vue = new VueSignup();
            $vue->afficherErreur('Ce nom d\'utilisateur est déjà utilisé.');
            return;
        }

        // Créer le compte
        creerCompte($username, $password);

        // Rediriger vers la page de succès
        header('Location: index.php?action=');
    }

    public function creerLocation() {
        // Récupérer les données du formulaire d'inscription
        $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
        $prix = isset($_POST['prix']) ? $_POST['prix'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $duree = isset($_POST['duree']) ? $_POST['duree'] : '';
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $selectedTags =  isset($_POST['tags']) ? $_POST['tags']:null;

        if(isset($image)) {
            // Permet de renonner l'image
            $time = time();
            $image_name = $time . $image['name'];
            $image_tmp_name = $image['tmp_name'];
            $image_destination_path = "ressources/" . $image_name;
    
            // Make sure file is an image
            $allowed_files = ['png', 'jpg', 'jpeg', 'gif'];
            $extension = explode('.', $image_name);
            $extension = end($extension);
            if (in_array($extension, $allowed_files)) {
                move_uploaded_file($image_tmp_name, $image_destination_path);
            }
        }

        $id = $_SESSION['userId'];

        // Créer le compte
        $idAnnonce = creerAnnonce($id, $titre, $prix, $description, $duree, $image_name);


        if (isset($selectedTags)) {
            $_SESSION['tag'] = $selectedTags;

            creerLienTags($idAnnonce, $selectedTags);
        }
        // Rediriger vers la page de succès
        header('Location: index.php?action=');
    }

    public function connexion() {
        // Récupérer les données du formulaire de connexion
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
    
        // Vérifier les informations de connexion
        $userId = getUserId($username, $password);
        if ($userId) {
            // Connexion réussie
            // Enregistrer l'id de l'utilisateur dans la session
            session_start();
            $_SESSION['userId'] = $userId;
    
            // Rediriger vers la page d'accueil
            header('Location: index.php');
        } else {
            // Gérer l'erreur de connexion (par exemple, afficher un message d'erreur)
            require_once('vue/login.php');
            $vue = new VueLogin();
            $vue->afficherErreur('Nom d\'utilisateur ou mot de passe incorrect.');
        }
    }

    public function deconnexion() {
        // Détruire la session
        session_destroy();
    
        // Rediriger vers la page d'accueil
        header('Location: index.php');
        exit();
    }

    }

?>
