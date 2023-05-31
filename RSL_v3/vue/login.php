<?php
class VueLogin {
    public function afficherPageConnexion() {
        echo '<h1>Connexion</h1>';
        // Formulaire d'inscription
        echo '<form method="post" action="index.php?action=Connexion">';
        echo 'Nom d\'utilisateur : <input type="text" name="username"><br>';
        echo 'Mot de passe : <input type="password" name="password"><br>';
        echo '<input type="submit" value="Créer un compte">';
        echo '</form>';
    }

    public function afficherErreur($message) {
        echo '<h1>Erreur</h1>';
        echo '<p>' . $message . '</p>';
    }
}
?>
