<?php 
include 'includes/connection.php';
include 'includes/send_email.php'; // Or simple_mail.php

if(isset($_POST['submit'])) {
    $formation_id = $_GET['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    
    // Get formation details
    $sql = "SELECT f.*, c.name as cours_name, v.value as ville_name, p.value as pays_name,
            fd.date as formation_date
            FROM formations f 
            JOIN cours c ON f.cours_id = c.id 
            JOIN villes v ON f.ville_id = v.id
            JOIN pays p ON v.pays_id = p.id
            JOIN formation_dates fd ON f.id = fd.formation_id
            WHERE f.id = ?
            ORDER BY fd.date
            LIMIT 1";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$formation_id]);
    $formation = $stmt->fetch();
    
    // Insert inscription
    $sql = "INSERT INTO inscriptions (formation_id, firstName, lastName, email, phone, company) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$formation_id, $firstName, $lastName, $email, $phone, $company]);
    
    // Prepare email
    $emailSubject = "Confirmation d'inscription - " . $formation['cours_name'];
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background-color: #f4f4f4; }
            .info { margin: 10px 0; }
            .footer { text-align: center; padding: 20px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Confirmation d'inscription</h1>
            </div>
            <div class='content'>
                <p>Bonjour $firstName $lastName,</p>
                <p>Nous confirmons votre inscription à la formation suivante :</p>
                
                <div class='info'><strong>Formation :</strong> {$formation['cours_name']}</div>
                <div class='info'><strong>Lieu :</strong> {$formation['ville_name']}, {$formation['pays_name']}</div>
                <div class='info'><strong>Date :</strong> " . date('d/m/Y', strtotime($formation['formation_date'])) . "</div>
                <div class='info'><strong>Mode :</strong> {$formation['mode']}</div>
                <div class='info'><strong>Prix :</strong> {$formation['price']} DH</div>
                
                <p>Nous vous contacterons prochainement avec plus de détails.</p>
                
                <p>Cordialement,<br>L'équipe du Centre de Formation</p>
            </div>
            <div class='footer'>
                <p>Centre de Formation Professionnelle<br>
                contact@formation-center.ma | +212 5 22 123 456</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send email
    $emailSent = sendEmail($email, $emailSubject, $emailBody);
    
    if($emailSent) {
        echo "<script>alert('Inscription réussie! Un email de confirmation a été envoyé.'); window.location.href='formations.php';</script>";
    } else {
        echo "<script>alert('Inscription réussie! (Email non envoyé)'); window.location.href='formations.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h2>Formulaire d'inscription</h2>
        <?php
        // Show formation details
        if(isset($_GET['id'])) {
            $sql = "SELECT f.*, c.name as cours_name, v.value as ville_name
                    FROM formations f 
                    JOIN cours c ON f.cours_id = c.id 
                    JOIN villes v ON f.ville_id = v.id
                    WHERE f.id = ?";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([$_GET['id']]);
            $formation = $stmt->fetch();
            
            if($formation) {
                echo "<div class='formation-card'>";
                echo "<h3>Formation: ".$formation['cours_name']."</h3>";
                echo "<p><strong>Ville:</strong> ".$formation['ville_name']."</p>";
                echo "<p><strong>Prix:</strong> ".$formation['price']." DH</p>";
                echo "<p><strong>Mode:</strong> ".$formation['mode']."</p>";
                echo "</div>";
            }
        }
        ?>
        
        <form method="POST" onsubmit="return validateForm()" class="admin-form">
            <input type="text" name="firstName" placeholder="Prénom" required>
            <input type="text" name="lastName" placeholder="Nom" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Téléphone" required>
            <input type="text" name="company" placeholder="Entreprise">
            <button type="submit" name="submit" class="btn">S'inscrire</button>
        </form>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>