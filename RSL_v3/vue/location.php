<?php
class VueLocation {
    public function afficherPageLocation($tags) {
        echo '<h1>Créer une Location</h1>';
        // Formulaire de création d'une location
        echo '<form method="POST" action="index.php?action=creerLocation" enctype="multipart/form-data">';
        echo 'Titre : <input type="text" name="titre"><br>';
        echo 'Prix : <input type="text" name="prix"><br>';
        echo 'Description : <textarea name="description"></textarea><br>';
        echo 'Durée : <input type="text" name="duree"><br>';
        echo 'Image : <input type="file" name="image"><br>';
        
        // Afficher les checkboxes pour chaque tag
        foreach ($tags as $tag) {
            echo "<input type='checkbox' name='tags[]' value='" . $tag['id'] . "'>" . $tag['name'] . "<br>";
        }

        echo '<input type="submit" value="Créer la location">';
        echo '</form>';
    }

    public function afficherErreur($message) {
        echo '<h1>Erreur</h1>';
        echo '<p>' . $message . '</p>';
    }
}

?>
