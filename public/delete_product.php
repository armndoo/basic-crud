<?php
$page_title = "Elimina prodotto";
$page_css = "../css/delete.css";
include_once '../php-inc/header.php';
include_once '../php-inc/database.php';
include_once '../php-inc/product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

if($_POST && isset($_POST['object_id'])){
    $product->id = $_POST['object_id'];
    
    if($product->delete()){
        echo "<div class='alert alert-success'>Il prodotto è stato eliminato.</div>";
        echo "<a href='../php-pages/dashboard.php' class='link'>Torna indietro</a>";
    } else {
        echo "<div class='alert alert-danger'>Errore nella eliminazione.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Richiesta non valida o ID mancante.</div>";
}
include_once '../php-inc/footer.php';
?>
