<?php
include '../includes/connection.php'; // Adjust path if needed
$where = [];
$params = [];

if (!empty($_GET['domaine'])) {
    $where[] = 'domaine_id = ?';
    $params[] = $_GET['domaine'];
}
if (!empty($_GET['sujet'])) {
    $where[] = 'sujet_id = ?';
    $params[] = $_GET['sujet'];
}
if (!empty($_GET['cours'])) {
    $where[] = 'cours_id = ?';
    $params[] = $_GET['cours'];
}
if (!empty($_GET['ville'])) {
    $where[] = 'ville_id = ?';
    $params[] = $_GET['ville'];
}
if (!empty($_GET['search'])) {
    $where[] = '(nom LIKE ? OR description LIKE ?)';
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
}

$sql = "SELECT * FROM formations";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$stmt = $bdd->prepare($sql);
$stmt->execute($params);

// Loop to print formations as cards...

?>
