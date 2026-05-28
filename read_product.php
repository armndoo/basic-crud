<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['session_id']) ||  !isset($_SESSION['session_user'])) {
    session_unset();
    session_destroy();
  
    header("Location: index.html");
    exit();
}    define('INCLUDED', true);
    $page_title = "Prodotto";
    $page_css = "css/read.css";

    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/incs/isAdmin.php';
    $database = new Database();
    $db = $database->getConnection();
    checkAdmin($db);
    require_once __DIR__ . '/incs/header.php';

    echo " <div class='d-grid gap-2 d-md-flex justify-content-md-end'><a class='btn btn-primary'href='./dashboard.php'>Visualizza prodotti</a></div>";
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID');

    require_once __DIR__ . '/src/product.php';
    require_once __DIR__ . '/src/category.php';

    $product = new Product($db);
    $category = new Category($db);

    $product->id = $id;
    $product->readOne();

    echo "<table class='table table-hover table-responsive table-bordered'>";

        echo "<tr>";
            echo "<td>Nome</td>";
            echo "<td>{$product->name}</td>";
        echo "</tr>";

        echo "<tr>";
            echo "<td>Prezzo</td>";
            echo "<td>{$product->price}</td>";
        echo "</tr>";

        echo "<tr>";
            echo "<td>Descrizione</td>";
            echo "<td>{$product->description}</td>";
        echo "</tr>";

        echo "<tr>";
            echo "<td>Categoria</td>";
            echo "<td>";
                $category->id=$product->category_id;
                $category->readName();
                echo $category->name;
            echo "</td>";
        echo "</tr>";

    echo "</table>";

    require_once __DIR__ . '/incs/footer.php';

?>
