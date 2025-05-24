<?php 
include 'includes/connection.php';

if(isset($_POST['submit'])) {
    $formation_id = $_GET['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    
    $sql = "INSERT INTO inscriptions (formation_id, firstName, lastName, email, phone, company) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$formation_id, $firstName, $lastName, $email, $phone, $company]);
    
    echo "<script>alert('Inscription réussie!');</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Formulaire d'inscription</h2>
        <form method="POST">
            <input type="text" name="firstName" placeholder="Prénom" required>
            <input type="text" name="lastName" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Téléphone" required>
            <input type="text" name="company" placeholder="Entreprise">
            <button type="submit" name="submit" class="btn">S'inscrire</button>
        </form>
    </div>
</body>
</html>