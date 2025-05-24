<?php include 'includes/connection.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Nos Formations</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <!-- Same header as index.php -->
    </header>
    
    <div class="container">
        <h2>Nos Formations</h2>
        
        <!-- Filter form -->
        <form method="GET">
            <select name="domaine">
                <option value="">Tous les domaines</option>
                <?php
                $query = $bdd->query("SELECT * FROM domaines");
                while($domaine = $query->fetch()) {
                    echo "<option value='".$domaine['id']."'>".$domaine['name']."</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn">Filtrer</button>
        </form>
        
        <!-- Display formations -->
        <?php
        $sql = "SELECT f.*, c.name as cours_name, v.name as ville_name 
                FROM formations f 
                JOIN cours c ON f.cours_id = c.id 
                JOIN villes v ON f.ville_id = v.id";
        
        $formations = $bdd->query($sql);
        while($formation = $formations->fetch()) {
            echo "<div class='formation-card'>";
            echo "<h3>".$formation['cours_name']."</h3>";
            echo "<p>Ville: ".$formation['ville_name']."</p>";
            echo "<p>Prix: ".$formation['price']." DH</p>";
            echo "<p>Mode: ".$formation['mode']."</p>";
            echo "<a href='inscription.php?id=".$formation['id']."' class='btn'>S'inscrire</a>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>