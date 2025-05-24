<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard Admin</h1>
        <nav>
            <ul>
                <li><a href="gestion_pays.php">Gérer les pays</a></li>
                <li><a href="gestion_villes.php">Gérer les villes</a></li>
                <li><a href="gestion_domaines.php">Gérer les domaines</a></li>
                <li><a href="gestion_sujets.php">Gérer les sujets</a></li>
                <li><a href="gestion_cours.php">Gérer les cours</a></li>
                <li><a href="gestion_formations.php">Gérer les formations</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>