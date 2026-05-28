<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('INCLUDED', true);
require_once __DIR__ . "/config/database.php";

$database = new Database();
$db = $database->getConnection();

if (isset($_SESSION["session_id"])) {
    header("Location: ./dashboard.php");
    exit();
}

$msg = ""; 

if (isset($_POST["login"])) {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        $msg = "Inserisci username e password.";
    } else {
        $query = "
            SELECT username, password
            FROM users
            WHERE username = :username
        ";

        $check = $db->prepare($query);
        $check->bindParam(":username", $username, PDO::PARAM_STR);
        $check->execute();

        $user = $check->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["session_id"] = session_id();
            $_SESSION["session_user"] = $user["username"];
            
            header("Location: ./dashboard.php");
            exit();
        } else {
            $msg = "Credenziali utente errate.";
        }
    }
}
?>
<!doctype html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="./css/login.css" />
        <link rel="stylesheet" href="./css/dashboard.css" /> 
    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <h1>Login</h1>
            
            <?php if (!empty($msg)): ?>
                <div class="alert alert-info">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <input
                type="text"
                id="username"
                name="username"
                required
                maxlength="50"
                placeholder="Username o Email"
            />
            <input
                type="password"
                name="password"
                id="password"
                required
                placeholder="Password"
            />
           <button type="submit" name="login" class="btn btn-primary">Login</button>         
            <div style="margin-top: 15px; text-align: center;">
                <a href="./register.php" style="color: #4285f4; text-decoration: none; font-size: 13px;">
                    Non hai creato un account? Registrati adesso
                </a>
<br/>                <a href="./admin.php" style="color: #4285f4; text-decoration: none; font-size: 13px;"> Sei un amministratore? Clicca qui. </a>
                </div>
        </form>
    </body>
</html>
