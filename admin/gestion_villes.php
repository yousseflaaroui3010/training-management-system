<?php
session_start();
include '../includes/connection.php';

// Handle add
if(isset($_POST['add'])) {
    $value = $_POST['value'];
    $pays_id = $_POST['pays_id'];
    $sql = "INSERT INTO villes (value, pays_id) VALUES (?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$value, $pays_id]);
    header("Location: gestion_villes.php?success=added");
}

// Handle update
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $value = $_POST['value'];
    $pays_id = $_POST['pays_id'];
    $sql = "UPDATE villes SET value = ?, pays_id = ? WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$value, $pays_id, $id]);
    header("Location: gestion_villes.php?success=updated");
}

// Handle delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM villes WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_villes.php?success=deleted");
}

// Get data for edit
$editData = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM villes WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    $editData = $stmt->fetch();
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
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php 
                if($_GET['success'] == 'added') echo "Ville ajoutée avec succès!";
                if($_GET['success'] == 'updated') echo "Ville modifiée avec succès!";
                if($_GET['success'] == 'deleted') echo "Ville supprimée avec succès!";
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Add/Edit form -->
        <form method="POST" class="admin-form">
            <h3><?php echo $editData ? 'Modifier' : 'Ajouter'; ?> une ville</h3>
            <?php if($editData): ?>
                <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
            <?php endif; ?>
            <input type="text" name="value" placeholder="Nom de la ville" 
                   value="<?php echo $editData ? $editData['value'] : ''; ?>" required>
            <select name="pays_id" required>
                <option value="">Sélectionner un pays</option>
                <?php
                $pays_query = $bdd->query("SELECT * FROM pays ORDER BY value");
                while($pays = $pays_query->fetch()) {
                    $selected = ($editData && $editData['pays_id'] == $pays['id']) ? 'selected' : '';
                    echo "<option value='".$pays['id']."' $selected>".$pays['value']."</option>";
                }
                ?>
            </select>
            <button type="submit" name="<?php echo $editData ? 'update' : 'add'; ?>" class="btn">
                <?php echo $editData ? 'Modifier' : 'Ajouter'; ?>
            </button>
            <?php if($editData): ?>
                <a href="gestion_villes.php" class="btn btn-danger">Annuler</a>
            <?php endif; ?>
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
                    echo "<a href='gestion_villes.php?edit=".$ville['id']."' class='btn btn-warning btn-small'>Modifier</a> ";
                    echo "<a href='gestion_villes.php?delete=".$ville['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger btn-small'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>