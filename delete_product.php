<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['session_id']) ||  !isset($_SESSION['session_user'])) {
    session_unset();
    session_destroy();
  
    header("Location: index.html");
    exit();
}define('INCLUDED', true);
$page_title = "Elimina prodotto";
$page_css = "/css/delete.css";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/category.php';
require_once __DIR__ . '/src/product.php';
require_once __DIR__ . '/incs/isAdmin.php';
$database = new Database();
$db = $database->getConnection();
checkAdmin($db);

$product = new Product($db);

if($_POST && isset($_POST['object_id'])){
    $product->id = $_POST['object_id'];
    
    if($product->delete()){
        echo "<div class='alert alert-success'>Il prodotto è stato eliminato.</div>";
        echo "<a href='./dashboard.php' class='link'>Torna indietro</a>";
    } else {
        echo "<div class='alert alert-danger'>Errore nella eliminazione.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Richiesta non valida o ID mancante.</div>";
}
require_once __DIR__ . '/incs/footer.php';
?>
