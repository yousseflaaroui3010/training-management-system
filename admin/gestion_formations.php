<?php
session_start();
include '../includes/connection.php';

// Handle form submissions
if(isset($_POST['add'])) {
    $cours_id = $_POST['cours_id'];
    $ville_id = $_POST['ville_id'];
    $formateur_id = $_POST['formateur_id'];
    $price = $_POST['price'];
    $mode = $_POST['mode'];
    $dates = $_POST['dates']; // Array of dates
    
    // Insert formation
    $sql = "INSERT INTO formations (cours_id, ville_id, formateur_id, price, mode) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$cours_id, $ville_id, $formateur_id, $price, $mode]);
    
    $formation_id = $bdd->lastInsertId();
    
    // Insert dates
    foreach($dates as $date) {
        if($date) { // Skip empty dates
            $sql = "INSERT INTO formation_dates (formation_id, date) VALUES (?, ?)";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([$formation_id, $date]);
        }
    }
    
    header("Location: gestion_formations.php");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM formations WHERE id = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$id]);
    header("Location: gestion_formations.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Formations</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h2>Gestion des Formations</h2>
        
        <!-- Add form -->
        <form method="POST" class="admin-form">
            <h3>Créer une formation</h3>
            <select name="cours_id" required>
                <option value="">Sélectionner un cours</option>
                <?php
                $cours_query = $bdd->query("SELECT c.*, s.name as sujet_name 
                                           FROM cours c 
                                           JOIN sujets s ON c.sujet_id = s.id 
                                           ORDER BY s.name, c.name");
                while($cours = $cours_query->fetch()) {
                    echo "<option value='".$cours['id']."'>".$cours['sujet_name']." - ".$cours['name']."</option>";
                }
                ?>
            </select>
            
            <select name="ville_id" required>
                <option value="">Sélectionner une ville</option>
                <?php
                $villes_query = $bdd->query("SELECT v.*, p.value as pays_name 
                                            FROM villes v 
                                            JOIN pays p ON v.pays_id = p.id 
                                            ORDER BY p.value, v.value");
                while($ville = $villes_query->fetch()) {
                    echo "<option value='".$ville['id']."'>".$ville['pays_name']." - ".$ville['value']."</option>";
                }
                ?>
            </select>
            
            <select name="formateur_id" required>
                <option value="">Sélectionner un formateur</option>
                <?php
                $formateurs_query = $bdd->query("SELECT * FROM formateurs ORDER BY lastName, firstName");
                while($formateur = $formateurs_query->fetch()) {
                    echo "<option value='".$formateur['id']."'>".$formateur['firstName']." ".$formateur['lastName']."</option>";
                }
                ?>
            </select>
            
            <input type="number" name="price" placeholder="Prix (DH)" step="0.01" required>
            
            <select name="mode" required>
                <option value="">Mode de formation</option>
                <option value="Presentiel">Présentiel</option>
                <option value="Distanciel">Distanciel</option>
            </select>
            
            <h4>Dates de formation</h4>
            <div id="dates-container">
                <input type="date" name="dates[]" required>
            </div>
            <button type="button" onclick="addDate()" class="btn btn-success">Ajouter une date</button>
            
            <button type="submit" name="add" class="btn">Créer la formation</button>
        </form>
        
        <!-- List -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cours</th>
                    <th>Ville</th>
                    <th>Formateur</th>
                    <th>Prix</th>
                    <th>Mode</th>
                    <th>Dates</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT f.*, c.name as cours_name, v.value as ville_name,
                                     CONCAT(fo.firstName, ' ', fo.lastName) as formateur_name,
                                     GROUP_CONCAT(fd.date) as dates
                                     FROM formations f 
                                     JOIN cours c ON f.cours_id = c.id 
                                     JOIN villes v ON f.ville_id = v.id
                                     JOIN formateurs fo ON f.formateur_id = fo.id
                                     LEFT JOIN formation_dates fd ON f.id = fd.formation_id
                                     GROUP BY f.id
                                     ORDER BY f.id DESC");
                while($formation = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".$formation['id']."</td>";
                    echo "<td>".$formation['cours_name']."</td>";
                    echo "<td>".$formation['ville_name']."</td>";
                    echo "<td>".$formation['formateur_name']."</td>";
                    echo "<td>".$formation['price']." DH</td>";
                    echo "<td>".$formation['mode']."</td>";
                    echo "<td>".str_replace(',', '<br>', $formation['dates'])."</td>";
                    echo "<td>";
                    echo "<a href='gestion_formations.php?delete=".$formation['id']."' onclick='return confirm(\"Êtes-vous sûr?\")' class='btn btn-danger'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <script>
    function addDate() {
        var container = document.getElementById('dates-container');
        var input = document.createElement('input');
        input.type = 'date';
        input.name = 'dates[]';
        input.style.marginTop = '10px';
        container.appendChild(input);
    }
    </script>
</body>
</html>