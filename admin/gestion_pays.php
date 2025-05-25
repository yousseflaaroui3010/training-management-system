<?php
session_start();
include '../includes/connection.php';

// Handle add
if(isset($_POST['add'])) {
    $value = $_POST['value'];
    $sql = "INSERT INTO pays (value) VALUES (?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$value]);
    header("Location: gestion_pays.php?success=added");
}

// Handle update
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $value = $_POST['value'];
    $sql = "UPDATE pays SET value = ? WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$value, $id]);
    header("Location: gestion_pays.php?success=updated");
}

// Handle delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM pays WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_pays.php?success=deleted");
}

// Get data for edit
$editData = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM pays WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    $editData = $stmt->fetch();
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
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php 
                if($_GET['success'] == 'added') echo "Pays ajouté avec succès!";
                if($_GET['success'] == 'updated') echo "Pays modifié avec succès!";
                if($_GET['success'] == 'deleted') echo "Pays supprimé avec succès!";
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Add/Edit form -->
        <form method="POST" class="admin-form">
            <h3><?php echo $editData ? 'Modifier' : 'Ajouter'; ?> un pays</h3>
            <?php if($editData): ?>
                <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
            <?php endif; ?>
            <input type="text" name="value" placeholder="Nom du pays" 
                   value="<?php echo $editData ? $editData['value'] : ''; ?>" required>
            <button type="submit" name="<?php echo $editData ? 'update' : 'add'; ?>" class="btn">
                <?php echo $editData ? 'Modifier' : 'Ajouter'; ?>
            </button>
            <?php if($editData): ?>
                <a href="gestion_pays.php" class="btn btn-danger">Annuler</a>
            <?php endif; ?>
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
                    echo "<a href='gestion_pays.php?edit=".$pays['id']."' class='btn btn-warning btn-small'>Modifier</a> ";
                    echo "<a href='gestion_pays.php?delete=".$pays['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger btn-small'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>