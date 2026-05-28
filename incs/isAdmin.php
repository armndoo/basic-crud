<?php 
function isAdmin($db) {
    if (!isset($_SESSION['session_user'])) {
        return false;
    }

    $query = "SELECT role FROM users WHERE username = :user LIMIT 1";

    $stmt = $db ->prepare($query);
    $stmt->bindParam(":user", $_SESSION['session_user'], PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
    if($row && $row['role'] === "admin") {
        return true;
    }

    return false;
};

function checkAdmin($db) {
    $isAdmin = isAdmin($db);
    if (!$isAdmin) {
        if(isset($_SERVER["HTTP_REFERER"])) {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
            exit();
        } else {
            header("Location: index.html");
            exit();
        }
    }
}
?>
