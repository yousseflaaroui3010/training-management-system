<?php
session_start();
include '../includes/connection.php';

// Handle form submissions
if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "INSERT INTO domaines (name, description) VALUES (?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$name, $description]);
    header("Location: gestion_domaines.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM domaines WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_domaines.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Domaines</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Domaines</h2>
        
        <!-- Add form -->
        <form method="POST" class="admin-form">
            <h3>Ajouter un domaine</h3>
            <input type="text" name="name" placeholder="Nom du domaine" required>
            <textarea name="description" placeholder="Description" rows="3"></textarea>
            <button type="submit" name="add" class="btn">Ajouter</button>
        </form>
        
        <!-- List -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT * FROM domaines ORDER BY name");
                while($domaine = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$domaine['id']."</td>";
                    echo "<td>".$domaine['name']."</td>";
                    echo "<td>".$domaine['description']."</td>";
                    echo "<td>";
                    echo "<a href='gestion_domaines.php?delete=".$domaine['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>