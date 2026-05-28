<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('INCLUDED', true);
require_once __DIR__  . "/config/database.php";

$database = new Database();
$db = $database->getConnection();

$msg = ""; 
$msg2 = ""; 

if (isset($_POST["register"])) {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $email    = trim($_POST["email"] ?? "");
    $num_tel  = trim($_POST["num_tel"] ?? "");

    $isUsernameValid = filter_var($username, FILTER_VALIDATE_REGEXP, [
        "options" => ["regexp" => "/^[a-z\d_]{3,20}$/i"],
    ]);
    
    $pwdLength = mb_strlen($password);

    if (empty($username) || empty($password) || empty($email)) {
        $msg = "Compila tutti i campi obbligatori (Username, Email e Password).";
    } elseif (false === $isUsernameValid) {
        $msg = "Lo username non è valido. Sono ammessi solamente caratteri alfanumerici e l'underscore (3-20 caratteri).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "L'indirizzo email inserito non è valido.";
    } elseif ($pwdLength < 8 || $pwdLength > 20) {
        $msg = "La password deve essere compresa tra 8 e 20 caratteri.";
    } else {
        $query = "SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1";
        $check = $db->prepare($query);
        $check->bindParam(":username", $username, PDO::PARAM_STR);
        $check->bindParam(":email", $email, PDO::PARAM_STR);
        $check->execute();
        
        if ($check->rowCount() > 0) {
            $msg = "Username o Email già in uso.";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users (username, num_tel, email, password) 
                      VALUES (:username, :num_tel, :email, :password)";

            $insert = $db->prepare($query);
            $insert->bindParam(":username", $username, PDO::PARAM_STR);
            $insert->bindParam(":password", $password_hash, PDO::PARAM_STR);
            $insert->bindParam(":email", $email, PDO::PARAM_STR);
            $insert->bindValue(":num_tel", !empty($num_tel) ? $num_tel : null, PDO::PARAM_STR);
            $insert->execute();

            if ($insert->rowCount() > 0) {
                $msg2 = "Registrazione eseguita con successo! <a href='./login.php'>Accedi ora</a>";
            } else {
                $msg = "Errore durante l'inserimento dei dati nel database.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Registrazione</title>
  <link rel="stylesheet" href="./css/register.css" />
</head>
<body>

  <form method="post" action="">
    <h1>Registrazione</h1>
    
    <?php if (!empty($msg)): ?>
        <div class="message" style="margin-bottom: 15px; color: red;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($msg2)): ?>
        <div class="message" style="margin-bottom: 15px; color: green;">
            <?php echo $msg2; ?>
        </div>
    <?php endif; ?>

    <input type="text" id="username" name="username" maxlength="50" placeholder="Username" required />
    <input type="email" id="email" name="email" placeholder="Email" required />
    <input type="tel" id="num_tel" name="num_tel" placeholder="Numero di telefono" />
    <input type="password" id="password" name="password" required placeholder="Password" />
    
    <button type="submit" name="register">Registrati</button>
    <a href="./login.php">Hai già un account? Accedi ora</a>
  </form>

</body>
</html>
