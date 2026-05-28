<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['session_id']) ||  !isset($_SESSION['session_user'])) {
    session_unset();
    session_destroy();
  
    header("Location: index.html");
    exit();
}
    define('INCLUDED', true);
    $page_title = "Modifica prodotto";
    $page_css = "css/update.css";
    require_once __DIR__ .'/config/database.php';
    require_once __DIR__ . '/incs/isAdmin.php';

    $database = new Database();
    $db = $database->getConnection();
    checkAdmin($db);
    require_once __DIR__ .'/incs/header.php';
    

    echo '<div class="d-gap gap-2 d-md-flex justify-content-md-end"><a href="dashboard.php" class="btn btn-primary">Vedi prodotti</a></div>';

    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID');

    require_once __DIR__ .'/src/product.php';
    require_once __DIR__ .'/src/category.php';

    $product = new Product($db);
    $category = new Category($db);

    $product->id = $id;
    $product->readOne();
?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
        <table class="table table-hover table-responsive table-bordered">
            <tr>
            <td>Nome</td>
            <td><input type="text" name="name" value='<?php echo $product->name; ?>' class="form-control"></td>
            </tr>

            <tr>
            <td>Prezzo</td>
            <td><input type="text" name="price" value='<?php echo $product->price; ?>' class="form-control"></td>
            </tr>

            <tr>
            <td>Descrizione</td>
            <td><textarea name="description" class="form-control"><?php echo $product->description;?></textarea></td>
            </tr>

            <tr>
                <td>Categoria</td>
                <td>
                    <?php
                        $stmt = $category->read();
                        echo "<select class='form-control' name='category_id'>";
                        echo "<option>Seleziona una categoria...</option>";
                        while($row_category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $category_id = $row_category['id'];
                            $category_name = $row_category['name'];

                            if($product->category_id==$category_id) {
                                echo "<option value='{$category_id}' selected>{$category_name} </option>";
                            } else {
                                echo "<option value='{$category_id}'> {$category_name} </option>";
                            }

                        }
                        echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" class="btn btn-primary">Modifica</button></td>
            </tr>
        </table>
    </form>
<?php

    if($_POST) {
        $product->name = $_POST['name'];
        $product->price= $_POST['price'];
        $product->description= $_POST['description'];
        $product->category_id= $_POST['category_id'];

        if($product->update()) {
            echo " <div class='alert alert-success alert-dismissable'>Il prodotto è stato aggiornato con successo!</div>";
        } else {
            echo " <div class='alert alert-danger alert-dismissable'> Il prodotto non è stato aggiornato!</div> ";
        }
    }

    require_once __DIR__ .'/incs/footer.php';
?>

