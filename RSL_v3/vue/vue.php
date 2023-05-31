<?php
class Vue {
    public function afficherPageAccueil($annonces) {
        echo '<h1>Annonces</h1>';
        echo '<a href="index.php?action=signup">S\'inscrire</a>'; // Lien vers la page de sign up
        echo '<a href="index.php?action=login">Se connecté</a>';
        echo '<hr>';

        foreach ($annonces as $annonce) {
            echo '<h3>' . $annonce['name'] . '</h3>';
            echo '<p>Prix : ' . $annonce['price'] . '</p>';
            echo '<p>Description : ' . $annonce['description'] . '</p>';
            echo '<hr>';
        }
    }
}
?>
