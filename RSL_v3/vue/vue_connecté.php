<?php
class VueConnecté {
    public function afficherPageAccueilConnecté($annonces) {
        echo '<h1>Annonces</h1>';
        echo '<a href="index.php?action=logout">Déconnexion</a>';
        echo '<a href="index.php?action=Location">Mettre à louer</a>';
        echo '<hr>';
        var_dump($_SESSION);

        foreach ($annonces as $annonce) {
            echo '<h3>' . $annonce['name'] . '</h3>';
            echo '<p>Prix : ' . $annonce['price'] . '</p>';
            echo '<p>Description : ' . $annonce['description'] . '</p>';

            // Vérifier si le fichier image existe
            $imagePath = 'ressources/' . $annonce['image'];
            if (file_exists($imagePath)) {
                // Afficher l'image avec la taille spécifiée et le recadrage
                echo '<div style="width: 300px; height: 200px; overflow: hidden;">';
                echo '<img src="' . $imagePath . '" alt="Image de l\'annonce" style="width: 300px; height: 200px; object-fit: cover;">';
                echo '</div>';
            } else {
                // Afficher l'image par défaut
                echo '<div style="width: 300px; height: 200px; overflow: hidden;">';
                echo '<img src="ressources/default.png" alt="Image par défaut" style="width: 300px; height: 200px; object-fit: cover;">';
                echo '</div>';
            }

            echo '<hr>';
        }
    }
}


?>

