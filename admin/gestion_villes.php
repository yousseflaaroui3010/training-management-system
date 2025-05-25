<?php
session_start();
include '../includes/connection.php';

// Handle form submissions
if(isset($_POST['add'])) {
    $value = $_POST['value'];
    $pays_id = $_POST['pays_id'];
    $sql = "INSERT INTO villes (value, pays_id) VALUES (?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$value, $pays_id]);
    header("Location: gestion_villes.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM villes WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_villes.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Villes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Villes</h2>
        
        <!-- Add form -->
        <form method="POST" class="admin-form">
            <h3>Ajouter une ville</h3>
            <input type="text" name="value" placeholder="Nom de la ville" required>
            <select name="pays_id" required>
                <option value="">Sélectionner un pays</option>
                <?php
                $pays_query = $bdd->query("SELECT * FROM pays ORDER BY value");
                while($pays = $pays_query->fetch()) {
                    echo "<option value='".$pays['id']."'>".$pays['value']."</option>";
                }
                ?>
            </select>
            <button type="submit" name="add" class="btn">Ajouter</button>
        </form>
        
        <!-- List -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ville</th>
                    <th>Pays</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT v.*, p.value as pays_name 
                                     FROM villes v 
                                     JOIN pays p ON v.pays_id = p.id 
                                     ORDER BY p.value, v.value");
                while($ville = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$ville['id']."</td>";
                    echo "<td>".$ville['value']."</td>";
                    echo "<td>".$ville['pays_name']."</td>";
                    echo "<td>";
                    echo "<a href='gestion_villes.php?delete=".$ville['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>