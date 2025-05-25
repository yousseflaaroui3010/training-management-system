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
        <form method="GET" class="filter-form" id="filterForm">
            <select name="domaine" id="domaine" onchange="filterFormations()">
                <option value="">Tous les domaines</option>
                <?php
                $query = $bdd->query("SELECT * FROM domaines ORDER BY name");
                while($domaine = $query->fetch()) {
                    $selected = (isset($_GET['domaine']) && $_GET['domaine'] == $domaine['id']) ? 'selected' : '';
                    echo "<option value='".$domaine['id']."' $selected>".$domaine['name']."</option>";
                }
                ?>
            </select>
            
            <select name="sujet" id="sujet" onchange="filterFormations()">
                <option value="">Tous les sujets</option>
            </select>
            
            <select name="cours" id="cours" onchange="filterFormations()">
                <option value="">Tous les cours</option>
            </select>
            
            <select name="ville" id="ville" onchange="filterFormations()">
                <option value="">Toutes les villes</option>
                <?php
                $query = $bdd->query("SELECT v.*, p.value as pays_name 
                                     FROM villes v 
                                     JOIN pays p ON v.pays_id = p.id 
                                     ORDER BY p.value, v.value");
                while($ville = $query->fetch()) {
                    $selected = (isset($_GET['ville']) && $_GET['ville'] == $ville['id']) ? 'selected' : '';
                    echo "<option value='".$ville['id']."' $selected>".$ville['pays_name']." - ".$ville['value']."</option>";
                }
                ?>
            </select>
            
            <input type="text" name="search" id="search" placeholder="Rechercher..." 
                   value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
                   onkeyup="filterFormations()">
        </form>
        
        <!-- Loader -->
        <div id="loader" class="spinner" style="display: none;"></div>
        
        <!-- Display formations -->
        <div class="formations-grid" id="formationsContainer">
            <!-- Formations will be loaded here -->
        </div>
    </div>
    
    <script>
    // Load initial data
    window.onload = function() {
        loadSujets();
        loadCours();
        filterFormations();
    };
    
    // Load sujets based on selected domaine
    function loadSujets() {
        var domaine_id = document.getElementById('domaine').value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'ajax/get_sujets.php?domaine_id=' + domaine_id, true);
        xhr.onload = function() {
            if(this.status == 200) {
                document.getElementById('sujet').innerHTML = this.responseText;
                loadCours(); // Reload cours when sujet changes
            }
        };
        xhr.send();
    }
    
    // Load cours based on selected sujet
    function loadCours() {
        var sujet_id = document.getElementById('sujet').value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'ajax/get_cours.php?sujet_id=' + sujet_id, true);
        xhr.onload = function() {
            if(this.status == 200) {
                document.getElementById('cours').innerHTML = this.responseText;
            }
        };
        xhr.send();
    }
    
    // Filter formations
    function filterFormations() {
        var domaine = document.getElementById('domaine').value;
        var sujet = document.getElementById('sujet').value;
        var cours = document.getElementById('cours').value;
        var ville = document.getElementById('ville').value;
        var search = document.getElementById('search').value;
        
        // Show loader
        document.getElementById('loader').style.display = 'block';
        document.getElementById('formationsContainer').style.opacity = '0.5';
        
        var xhr = new XMLHttpRequest();
        var params = 'domaine=' + domaine + '&sujet=' + sujet + '&cours=' + cours + 
                     '&ville=' + ville + '&search=' + search;
        
        xhr.open('GET', 'ajax/filter_formations.php?' + params, true);
        xhr.onload = function() {
            if(this.status == 200) {
                document.getElementById('formationsContainer').innerHTML = this.responseText;
                document.getElementById('loader').style.display = 'none';
                document.getElementById('formationsContainer').style.opacity = '1';
            }
        };
        xhr.send();
    }
    
    // Update filters when domaine changes
    document.getElementById('domaine').addEventListener('change', function() {
        loadSujets();
    });
    
    // Update filters when sujet changes
    document.getElementById('sujet').addEventListener('change', function() {
        loadCours();
    });
    </script>
</body>
</html>