<?php
session_start();
include '../includes/connection.php';

// Handle form submissions
if(isset($_POST['add'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $description = $_POST['description'];
    
    // Handle file upload
    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = '../images/formateurs/';
        if(!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $photo = time() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo);
    }
    
    $sql = "INSERT INTO formateurs (firstName, lastName, description, photo) VALUES (?, ?, ?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$firstName, $lastName, $description, $photo]);
    header("Location: gestion_formateurs.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM formateurs WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_formateurs.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Formateurs</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Formateurs</h2>
        
        <!-- Add form -->
        <form method="POST" class="admin-form" enctype="multipart/form-data">
            <h3>Ajouter un formateur</h3>
            <input type="text" name="firstName" placeholder="Prénom" required>
            <input type="text" name="lastName" placeholder="Nom" required>
            <textarea name="description" placeholder="Description" rows="4"></textarea>
            <input type="file" name="photo" accept="image/*">
            <button type="submit" name="add" class="btn">Ajouter</button>
        </form>
        
        <!-- List -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT * FROM formateurs ORDER BY lastName, firstName");
                while($formateur = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$formateur['id']."</td>";
                    echo "<td>";
                    if($formateur['photo']) {
                        echo "<img src='../images/formateurs/".$formateur['photo']."' width='50' height='50' style='object-fit: cover;'>";
                    }
                    echo "</td>";
                    echo "<td>".$formateur['firstName']."</td>";
                    echo "<td>".$formateur['lastName']."</td>";
                    echo "<td>".substr($formateur['description'], 0, 50)."...</td>";
                    echo "<td>";
                    echo "<a href='gestion_formateurs.php?delete=".$formateur['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>