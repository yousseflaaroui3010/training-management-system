<?php
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<header class="admin-header">
    <div class="container">
        <h1>Administration - Centre de Formation</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="gestion_pays.php">Pays</a></li>
                <li><a href="gestion_villes.php">Villes</a></li>
                <li><a href="gestion_formateurs.php">Formateurs</a></li>
                <li><a href="gestion_domaines.php">Domaines</a></li>
                <li><a href="gestion_sujets.php">Sujets</a></li>
                <li><a href="gestion_cours.php">Cours</a></li>
                <li><a href="gestion_formations.php">Formations</a></li>
                <li><a href="gestion_inscriptions.php">Inscriptions</a></li>
                <li style="float:right"><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </div>
</header>