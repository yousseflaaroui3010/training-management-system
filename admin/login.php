<?php
session_start();
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Simple authentication (improve this in production)
    if($username == "admin" && $password == "admin123") {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion Admin</h2>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" name="login" class="btn">Se connecter</button>
        </form>
    </div>
</body>
</html>