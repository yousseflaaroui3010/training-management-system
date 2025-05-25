<?php
session_start();
include '../includes/connection.php';

// Update payment status
if(isset($_GET['pay'])) {
    $id = $_GET['pay'];
    $sql = "UPDATE inscriptions SET paid = 1 WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_inscriptions.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM inscriptions WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_inscriptions.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Inscriptions</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Inscriptions</h2>
        
        <!-- List -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Formation</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Entreprise</th>
                    <th>Payé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT i.*, c.name as cours_name, v.value as ville_name,
                                     f.price, f.mode
                                     FROM inscriptions i 
                                     JOIN formations f ON i.formation_id = f.id
                                     JOIN cours c ON f.cours_id = c.id 
                                     JOIN villes v ON f.ville_id = v.id
                                     ORDER BY i.id DESC");
                while($inscription = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$inscription['id']."</td>";
                    echo "<td>".$inscription['cours_name']." - ".$inscription['ville_name']."</td>";
                    echo "<td>".$inscription['firstName']." ".$inscription['lastName']."</td>";
                    echo "<td>".$inscription['email']."</td>";
                    echo "<td>".$inscription['phone']."</td>";
                    echo "<td>".$inscription['company']."</td>";
                    echo "<td>";
                    if($inscription['paid']) {
                        echo "<span style='color: green;'>✓ Payé</span>";
                    } else {
                        echo "<span style='color: red;'>✗ Non payé</span>";
                    }
                    echo "</td>";
                    echo "<td>";
                    if(!$inscription['paid']) {
                        echo "<a href='gestion_inscriptions.php?pay=".$inscription['id']."' class='btn btn-success'>Marquer payé</a> ";
                    }
                    echo "<a href='gestion_inscriptions.php?delete=".$inscription['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>