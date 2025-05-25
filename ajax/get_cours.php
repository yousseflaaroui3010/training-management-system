<?php
include '../includes/connection.php';

$sujet_id = isset($_GET['sujet_id']) ? $_GET['sujet_id'] : '';

echo '<option value="">Tous les cours</option>';

if($sujet_id) {
    $sql = "SELECT * FROM cours WHERE sujet_id = ? ORDER BY name";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$sujet_id]);
} else {
    $sql = "SELECT * FROM cours ORDER BY name";
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
}

while($cours = $stmt->fetch()) {
    echo '<option value="'.$cours['id'].'">'.$cours['name'].'</option>';
}
?>