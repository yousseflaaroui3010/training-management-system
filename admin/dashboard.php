<?php
session_start();
include '../includes/connection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container">
        <h1>Dashboard Admin</h1>
        
        <div class="dashboard-stats">
            <?php
            // Get statistics
            $stats = [
                'formations' => $bdd->query("SELECT COUNT(*) FROM formations")->fetchColumn(),
                'inscriptions' => $bdd->query("SELECT COUNT(*) FROM inscriptions")->fetchColumn(),
                'formateurs' => $bdd->query("SELECT COUNT(*) FROM formateurs")->fetchColumn(),
                'cours' => $bdd->query("SELECT COUNT(*) FROM cours")->fetchColumn(),
            ];
            ?>
            
            <div class="metrics-grid">
                <div class="metric-card">
                    <h4><?php echo $stats['formations']; ?></h4>
                    <p>Formations</p>
                </div>
                <div class="metric-card">
                    <h4><?php echo $stats['inscriptions']; ?></h4>
                    <p>Inscriptions</p>
                </div>
                <div class="metric-card">
                    <h4><?php echo $stats['formateurs']; ?></h4>
                    <p>Formateurs</p>
                </div>
                <div class="metric-card">
                    <h4><?php echo $stats['cours']; ?></h4>
                    <p>Cours</p>
                </div>
            </div>
        </div>
        
        <h2>Dernières inscriptions</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Formation</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $bdd->query("SELECT i.*, c.name as cours_name, 
                                     CONCAT(i.firstName, ' ', i.lastName) as full_name
                                     FROM inscriptions i 
                                     JOIN formations f ON i.formation_id = f.id
                                     JOIN cours c ON f.cours_id = c.id
                                     ORDER BY i.id DESC
                                     LIMIT 5");
                while($inscription = $query->fetch()) {
                    echo "<tr>";
                    echo "<td>".date('d/m/Y')."</td>";
                    echo "<td>".$inscription['full_name']."</td>";
                    echo "<td>".$inscription['cours_name']."</td>";
                    echo "<td>".($inscription['paid'] ? '<span style="color:green">Payé</span>' : '<span style="color:red">Non payé</span>')."</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>