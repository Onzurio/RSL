<?

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