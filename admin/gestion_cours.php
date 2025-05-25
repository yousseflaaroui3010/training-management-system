<?php
session_start();
include '../includes/connection.php';

// Handle form submissions
if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $content = $_POST['content'];
    $description = $_POST['description'];
    $audience = $_POST['audience'];
    $duration = $_POST['duration'];
    $testIncluded = isset($_POST['testIncluded']) ? 1 : 0;
    $testContent = $_POST['testContent'];
    $sujet_id = $_POST['sujet_id'];
    
    // Handle logo upload
    $logo = '';
    if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $upload_dir = '../images/cours/';
        if(!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $logo = time() . '_' . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $logo);
    }
    
    $sql = "INSERT INTO cours (name, content, description, audience, duration, testIncluded, testContent, logo, sujet_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$name, $content, $description, $audience, $duration, $testIncluded, $testContent, $logo, $sujet_id]);
    header("Location: gestion_cours.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM cours WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_cours.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Cours</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Cours</h2>
        
        <!-- Add form -->
        <form method="POST" class="admin-form" enctype="multipart/form-data">
            <h3>Ajouter un cours</h3>
            <input type="text" name="name" placeholder="Nom du cours" required>
            <textarea name="content" placeholder="Contenu" rows="4" required></textarea>
            <textarea name="description" placeholder="Description" rows="3" required></textarea>
            <input type="text" name="audience" placeholder="Audience cible" required>
            <input type="number" name="duration" placeholder="Durée (en jours)" required>
            <label>
                <input type="checkbox" name="testIncluded"> Test inclus
            </label>
            <textarea name="testContent" placeholder="Contenu du test" rows="3"></textarea>
            <select name="sujet_id" required>
                <option value="">Sélectionner un sujet</option>
                <?php
                $sujets_query = $bdd->query("SELECT s.*, d.name as domaine_name 
                                            FROM sujets s 
                                            JOIN domaines d ON s.domaine_id = d.id 
                                            ORDER BY d.name, s.name");
                while($sujet = $sujets_query->fetch()) {
                    echo "<option value='".$sujet['id']."'>".$sujet['domaine_name']." - ".$sujet['name']."</option>";
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
                    <th>Sujet</th>
                    <th>Durée</th>
                    <th>Test</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT c.*, s.name as sujet_name 
                                     FROM cours c 
                                     JOIN sujets s ON c.sujet_id = s.id 
                                     ORDER BY s.name, c.name");
                while($cours = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$cours['id']."</td>";
                    echo "<td>";
                    if($cours['logo']) {
                        echo "<img src='../images/cours/".$cours['logo']."' width='50' height='50' style='object-fit: cover;'>";
                    }
                    echo "</td>";
                    echo "<td>".$cours['name']."</td>";
                    echo "<td>".$cours['sujet_name']."</td>";
                    echo "<td>".$cours['duration']." jours</td>";
                    echo "<td>".($cours['testIncluded'] ? 'Oui' : 'Non')."</td>";
                    echo "<td>";
                    echo "<a href='gestion_cours.php?delete=".$cours['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>