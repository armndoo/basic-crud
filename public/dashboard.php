<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['session_id']) ||  !isset($_SESSION['session_user'])) {
    session_unset();
    session_destroy();
  
    header("Location: login.php");
    exit();
}

define('INCLUDED', true);
    $page_title = "Dashboard";
    $page_css = "../css/dashboard.css";
    include_once "../php-inc/header.php";
    
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $records_per_page = 10;

    $from_record_num = ($page * $records_per_page) - $records_per_page;

    include_once '../php-inc/database.php';
    include_once '../php-inc/category.php';
    include_once '../php-inc/product.php';

    $database = new Database();
    $db = $database->getConnection();

    $product = new Product($db);
    $category = new Category($db);

    $stmt = $product->readAll($from_record_num, $records_per_page);
    $num = $stmt->rowCount();

    $page_url = "dashboard.php?";
    $total_rows = $product->countAll();

    include_once '../php-inc/paging.php';

    echo '<div class="d-flex justify-content-md-end gap-2 mb-3"><a href="../logout.php" class="btn btn-primary">Logout</a></div>';
    echo '<div class="d-flex justify-content-md-end gap-2 mb-3"><a href="../index.html" class="btn btn-primary">Vedi prodotti</a></div>';

    if($num > 0) {
        echo "<table class='table table-hover table-responsive caption-top'>";
        echo "<caption>Lista Prodotti</caption>";
        echo "<thead>";
        echo "<tr>";
            echo "<th scope='col'>Prodotto</th>";
            echo "<th scope='col'>Prezzo</th>";
            echo "<th scope='col'>Descrizione</th>";
            echo "<th scope='col'>Categoria</th>";
            echo "<th scope='col'>Azioni</th>";
        echo"</tr>";
        echo "</thead>";
        echo "<tbody>"; 

               

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
    
        $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safe_price = htmlspecialchars($price, ENT_QUOTES, 'UTF-8');
        $safe_description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        $safe_id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

        echo "<tr>"; 
        echo "<td>{$safe_name}</td>";
        echo "<td>{$safe_price} €</td>";
        echo "<td>{$safe_description}</td>";
        echo "<td>";
        $category->id = $category_id;
        $category->readName();
        echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); 
        echo "</td>";
        echo "<td>";
        echo "<div style='display: flex; gap: 5px; align-items: center;'>
            <a href='read_product.php?id={$safe_id}' class='btn btn-primary btn-sm'>
                <span class='glyphicon glyphicon-list'></span> Leggi
            </a>
            <a href='update_product.php?id={$safe_id}' class='btn btn-info btn-sm'>
                <span class='glyphicon glyphicon-edit'></span> Modifica
            </a>
            <form action='delete_product.php' method='post' style='margin:0;' onsubmit='return confirm(\"Sei sicuro di voler eliminare questo prodotto?\");'>
                <input type='hidden' name='object_id' value='{$safe_id}'>
                <button type='submit' class='btn btn-danger btn-sm'>
                    <span class='glyphicon glyphicon-remove'></span> Elimina
                </button>
            </form>
        </div>";
        echo "</td>";
        echo "</tr>";
    }
echo "</tbody>";
echo "</table>";        
    } else {
        echo "<div class='alert alert-info'>Nessun prodotto trovato!</div>";
    }

    echo '<div class="d-flex justify-content-md-end gap-2 mt-3"><a href="create_product.php" class="btn btn-success">Inserisci prodotto</a></div>';

    include_once "../php-inc/footer.php";
?>
