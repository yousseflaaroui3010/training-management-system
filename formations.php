<?php include 'includes/connection.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Nos Formations</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h2>Nos Formations</h2>
        
        <!-- Filter form -->
        <form method="GET" class="filter-form">
            <select name="domaine">
                <option value="">Tous les domaines</option>
                <?php
                $query = $bdd->query("SELECT * FROM domaines");
                while($domaine = $query->fetch()) {
                    $selected = (isset($_GET['domaine']) && $_GET['domaine'] == $domaine['id']) ? 'selected' : '';
                    echo "<option value='".$domaine['id']."' $selected>".$domaine['name']."</option>";
                }
                ?>
            </select>
            
            <select name="ville">
                <option value="">Toutes les villes</option>
                <?php
                $query = $bdd->query("SELECT * FROM villes");
                while($ville = $query->fetch()) {
                    $selected = (isset($_GET['ville']) && $_GET['ville'] == $ville['id']) ? 'selected' : '';
                    echo "<option value='".$ville['id']."' $selected>".$ville['value']."</option>";
                }
                ?>
            </select>
            
            <button type="submit" class="btn">Filtrer</button>
        </form>
        
        <!-- Display formations -->
        <div class="formations-grid">
        <?php
        $sql = "SELECT f.*, c.name as cours_name, v.value as ville_name, 
                s.name as sujet_name, d.name as domaine_name,
                GROUP_CONCAT(fd.date) as dates
                FROM formations f 
                JOIN cours c ON f.cours_id = c.id 
                JOIN villes v ON f.ville_id = v.id
                JOIN sujets s ON c.sujet_id = s.id
                JOIN domaines d ON s.domaine_id = d.id
                LEFT JOIN formation_dates fd ON f.id = fd.formation_id
                WHERE 1=1";
        
        // Add filters
        if(isset($_GET['domaine']) && $_GET['domaine'] != '') {
            $sql .= " AND d.id = " . intval($_GET['domaine']);
        }
        if(isset($_GET['ville']) && $_GET['ville'] != '') {
            $sql .= " AND v.id = " . intval($_GET['ville']);
        }
        
        $sql .= " GROUP BY f.id";
        
        $formations = $bdd->query($sql);
        while($formation = $formations->fetch()) {
            echo "<div class='formation-card'>";
            echo "<h3>".$formation['cours_name']."</h3>";
            echo "<p><strong>Domaine:</strong> ".$formation['domaine_name']."</p>";
            echo "<p><strong>Sujet:</strong> ".$formation['sujet_name']."</p>";
            echo "<p><strong>Ville:</strong> ".$formation['ville_name']."</p>";
            echo "<p><strong>Prix:</strong> ".$formation['price']." DH</p>";
            echo "<p><strong>Mode:</strong> ".$formation['mode']."</p>";
            if($formation['dates']) {
                echo "<p><strong>Dates:</strong> ".str_replace(',', ', ', $formation['dates'])."</p>";
            }
            echo "<a href='inscription.php?id=".$formation['id']."' class='btn'>S'inscrire</a>";
            echo "</div>";
        }
        ?>
        </div>
    </div>
</body>
</html>