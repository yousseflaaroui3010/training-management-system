<?php 
include 'includes/connection.php';

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Here you would normally send an email
    // For now, we'll just show a success message
    $success = "Votre message a été envoyé avec succès!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h2>Contactez-nous</h2>
        
        <?php if(isset($success)): ?>
            <div style="background: #27ae60; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 30px;">
            <div>
                <h3>Informations de contact</h3>
                <p><strong>Adresse:</strong><br>
                123 Boulevard Mohamed V<br>
                Casablanca, Maroc</p>
                
                <p><strong>Téléphone:</strong><br>
                +212 5 22 123 456</p>
                
                <p><strong>Email:</strong><br>
                contact@formation-center.ma</p>
                
                <p><strong>Horaires:</strong><br>
                Lundi - Vendredi: 9h00 - 18h00<br>
                Samedi: 9h00 - 13h00</p>
            </div>
            
            <div>
                <h3>Envoyer un message</h3>
                <form method="POST" onsubmit="return validateForm()">
                    <input type="text" name="name" placeholder="Votre nom" required>
                    <input type="email" name="email" id="email" placeholder="Votre email" required>
                    <input type="text" name="subject" placeholder="Sujet" required>
                    <textarea name="message" placeholder="Votre message" rows="5" required></textarea>
                    <button type="submit" name="submit" class="btn">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="js/validation.js"></script>
</body>
</html>