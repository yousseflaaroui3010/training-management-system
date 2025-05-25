<?php
session_start();
include '../includes/connection.php';

// Handle form submissions
if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $shortDescription = $_POST['shortDescription'];
    $longDescription = $_POST['longDescription'];
    $individualBenefit = $_POST['individualBenefit'];
    $businessBenefit = $_POST['businessBenefit'];
    $domaine_id = $_POST['domaine_id'];
    
    // Handle logo upload
    $logo = '';
    if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $upload_dir = '../images/sujets/';
        if(!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $logo = time() . '_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $logo);
    }
    
    $sql = "INSERT INTO sujets (name, shortDescription, longDescription, individualBenefit, businessBenefit, logo, domaine_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$name, $shortDescription, $longDescription, $individualBenefit, $businessBenefit, $logo, $domaine_id]);
    header("Location: gestion_sujets.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM sujets WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_sujets.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Sujets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Sujets</h2>
        
        <!-- Add form -->
        <form method="POST" class="admin-form" enctype="multipart/form-data">
            <h3>Ajouter un sujet</h3>
            <input type="text" name="name" placeholder="Nom du sujet" required>
            <textarea name="shortDescription" placeholder="Description courte" rows="2" required></textarea>
            <textarea name="longDescription" placeholder="Description longue" rows="4"></textarea>
            <textarea name="individualBenefit" placeholder="Bénéfice individuel" rows="2"></textarea>
            <textarea name="businessBenefit" placeholder="Bénéfice entreprise" rows="2"></textarea>
            <select name="domaine_id" required>
                <option value="">Sélectionner un domaine</option>
                <?php
                $domaines_query = $bdd->query("SELECT * FROM domaines ORDER BY name");
                while($domaine = $domaines_query->fetch()) {
                    echo "<option value='".$domaine['id']."'>".$domaine['name']."</option>";
                }
                ?>
            </select>
            <input type="file" name="logo" accept="image/*">
            <button type="submit" name="add" class="btn">Ajouter</button>
        </form>
        
        <!-- List -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Logo</th>
                    <th>Nom</th>
                    <th>Domaine</th>
                    <th>Description courte</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT s.*, d.name as domaine_name 
                                     FROM sujets s 
                                     JOIN domaines d ON s.domaine_id = d.id 
                                     ORDER BY d.name, s.name");
                while($sujet = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$sujet['id']."</td>";
                    echo "<td>";
                    if($sujet['logo']) {
                        echo "<img src='../images/sujets/".$sujet['logo']."' width='50' height='50' style='object-fit: cover;'>";
                    }
                    echo "</td>";
                    echo "<td>".$sujet['name']."</td>";
                    echo "<td>".$sujet['domaine_name']."</td>";
                    echo "<td>".substr($sujet['shortDescription'], 0, 50)."...</td>";
                    echo "<td>";
                    echo "<a href='gestion_sujets.php?delete=".$sujet['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>