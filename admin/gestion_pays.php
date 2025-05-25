<?php
session_start();
include '../includes/connection.php';

// Handle form submissions
if(isset($_POST['add'])) {
    $value = $_POST['value'];
    $sql = "INSERT INTO pays (value) VALUES (?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$value]);
    header("Location: gestion_pays.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM pays WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_pays.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Pays</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Pays</h2>
        
        <!-- Add form -->
        <form method="POST" class="admin-form">
            <h3>Ajouter un pays</h3>
            <input type="text" name="value" placeholder="Nom du pays" required>
            <button type="submit" name="add" class="btn">Ajouter</button>
        </form>
        
        <!-- List -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT * FROM pays ORDER BY value");
                while($pays = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$pays['id']."</td>";
                    echo "<td>".$pays['value']."</td>";
                    echo "<td>";
                    echo "<a href='gestion_pays.php?delete=".$pays['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
