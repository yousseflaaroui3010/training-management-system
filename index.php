<?php include 'includes/connection.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Centre de Formation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <section class="hero">
            <h2>Bienvenue au Centre de Formation Professionnelle</h2>
            <p>Votre partenaire pour le développement des compétences</p>
        </section>
        
        <!-- Company presentation -->
        <section class="about">
            <h3>À propos de nous</h3>
            <p>Nous sommes un centre de formation spécialisé dans les domaines du Management et de l'Informatique. 
            Avec plus de 10 ans d'expérience, nous proposons des formations de qualité adaptées aux besoins du marché.</p>
        </section>
        
        <!-- Performance metrics -->
        <section class="metrics">
            <h3>Nos performances</h3>
            <div class="metrics-grid">
                <div class="metric-card">
                    <h4>95%</h4>
                    <p>Taux de satisfaction</p>
                </div>
                <div class="metric-card">
                    <h4>90%</h4>
                    <p>Taux de succès</p>
                </div>
                <div class="metric-card">
                    <h4>85%</h4>
                    <p>Taux de couverture</p>
                </div>
            </div>
        </section>
        
        <!-- Featured formations -->
        <section class="featured">
            <h3>Formations à venir</h3>
            <div class="formations-grid">
            <?php
            $sql = "SELECT f.*, c.name as cours_name, v.value as ville_name, 
                    MIN(fd.date) as next_date
                    FROM formations f 
                    JOIN cours c ON f.cours_id = c.id 
                    JOIN villes v ON f.ville_id = v.id
                    JOIN formation_dates fd ON f.id = fd.formation_id
                    WHERE fd.date >= CURDATE()
                    GROUP BY f.id
                    ORDER BY next_date
                    LIMIT 3";
            
            $formations = $bdd->query($sql);
            while($formation = $formations->fetch()) {
                echo "<div class='formation-card'>";
                echo "<h4>".$formation['cours_name']."</h4>";
                echo "<p>📍 ".$formation['ville_name']."</p>";
                echo "<p>📅 ".date('d/m/Y', strtotime($formation['next_date']))."</p>";
                echo "<p>💰 ".$formation['price']." DH</p>";
                echo "<a href='inscription.php?id=".$formation['id']."' class='btn'>S'inscrire</a>";
                echo "</div>";
            }
            ?>
            </div>
        </section>
    </div>
</body>
</html>