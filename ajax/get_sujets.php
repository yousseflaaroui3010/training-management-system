<?php
include '../includes/connection.php';

$domaine_id = isset($_GET['domaine_id']) ? $_GET['domaine_id'] : '';

echo '<option value="">Tous les sujets</option>';

if($domaine_id) {
    $sql = "SELECT * FROM sujets WHERE domaine_id = ? ORDER BY name";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$domaine_id]);
} else {
    $sql = "SELECT * FROM sujets ORDER BY name";
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
}

while($sujet = $stmt->fetch()) {
    echo '<option value="'.$sujet['id'].'">'.$sujet['name'].'</option>';
}
?>