<?php
include 'config.php';

function getAnnonces() {
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    $limit = '';  // Par défaut, pas de limitation

    // Vérifier si l'utilisateur n'est pas connecté
    if (!isset($_SESSION['userId'])) {
        $limit = 'LIMIT 6';  // Limiter à 6 annonces
    }

    $resultat = $mysqli->query("SELECT * FROM annonces $limit");

    $annonces = array();

    while ($row = $resultat->fetch_assoc()) {
        $annonces[] = $row;
    }

    $resultat->free();
    $mysqli->close();

    return $annonces;
}

function getTags() {
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    $resultat = $mysqli->query("SELECT * FROM tags");

    $tags = array();

    while ($row = $resultat->fetch_assoc()) {
        $tags[] = $row;
    }

    $resultat->free();
    $mysqli->close();

    return $tags;
}


function compteExiste($username) {
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $resultat = $stmt->get_result();
    $row = $resultat->fetch_assoc();

    $count = $row['count'];

    $resultat->free();
    $stmt->close();
    $mysqli->close();

    return $count > 0;
}

function creerCompte($username, $password) {
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();
}

function creerAnnonce($id, $titre, $prix, $description, $duree, $image) {
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO annonces (idAuthor, name, price, description, maxDuration, image ) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss",$id, $titre, $prix, $description, $duree, $image);
    $stmt->execute();

    $lastInsertId = $stmt->insert_id;

    $stmt->close();
    $mysqli->close();

    return $lastInsertId;
}

function creerLienTags($id, $tags){
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO tag_annonces (idTag, idAnnonce) VALUES (?, ?)");
    
    foreach ($tags as $tag) {
        $stmt->bind_param("ss", $tag, $id);
        $stmt->execute();
    }


    $stmt->close();
    $mysqli->close();


}


function loginUtilisateur($username, $password) {
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    // Vérifier si le compte existe
    if (!compteExiste($username)) {
        $mysqli->close();
        return false;
    }

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $resultat = $stmt->get_result();
    $row = $resultat->fetch_assoc();

    // Vérifier le mot de passe
    if ($row['password'] !== $password) {
        $resultat->free();
        $stmt->close();
        $mysqli->close();
        return false;
    }

    // Connexion réussie
    $resultat->free();
    $stmt->close();
    $mysqli->close();

    return true;
}

function getUserId($username, $password) {
    global $BDD_host, $BDD_user, $BDD_password, $BDD_base;

    $mysqli = new mysqli($BDD_host, $BDD_user, $BDD_password, $BDD_base);

    if ($mysqli->connect_errno) {
        // Gérer l'erreur de connexion à la base de données
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($userId);

    if ($stmt->fetch()) {
        $stmt->close();
        $mysqli->close();
        return $userId;
    } else {
        $stmt->close();
        $mysqli->close();
        return false;
    }
}






?>
