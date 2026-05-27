<?php
    $page_title = "Prodotto";
    $page_css = "../css/read.css";
    include_once '../php-inc/header.php';


    echo " <div class='d-grid gap-2 d-md-flex justify-content-md-end'><a class='btn btn-primary'href='./dashboard.php'>Visualizza prodotti</a></div>";
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID');

    include_once '../php-inc/database.php';
    include_once '../php-inc/product.php';
    include_once '../php-inc/category.php';

    $database = new Database();
    $db = $database->getConnection();

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

    include_once "../php-inc/footer.php";

?>
