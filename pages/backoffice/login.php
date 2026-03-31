<?php
require_once 'config.php';

// Si déjà connecté, rediriger vers l'index
if (isLoggedIn()) {
    header('Location: accueil?view=home');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $pdo = dbConnection();
        if (verifyUserCredentials($pdo, $username, $password)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: accueil?view=home');
            exit();
        } else {
            $error = 'Identifiants incorrects.';
        }
    } catch (PDOException $e) {
        $error = 'Erreur de connexion à la base de données.';
    }
}

$theme = getTheme();
?>

<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>VertoNews — Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <script>
        (function() {
            let theme = localStorage.getItem('vertonews_theme');
            if (!theme) {
                theme = 'light';
                localStorage.setItem('vertonews_theme', theme);
            }
            document.documentElement.setAttribute('data-theme', theme);
            document.cookie = "vertonews_theme=" + theme + "; path=/";
        })();
    </script>
</head>
<body>
    <div class="login-screen active">
        <div class="login-bg">
            <div class="login-bg-grid"></div>
        </div>
        <div class="login-card">
            <div class="login-logo">
                <span class="logo-verto">VERTO</span><span class="logo-news">NEWS</span>
                <p class="login-subtitle">Backoffice Administration</p>
            </div>
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label>Identifiant</label>
                    <input type="text" name="username" placeholder="admin" value="admin" required />
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••" value="admin" required />
                </div>
                <?php if ($error): ?>
                <div class="login-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <button type="submit" class="btn-login">Se connecter →</button>
            </form>
        </div>
    </div>
</body>
</html>