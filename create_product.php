<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['session_id']) || !isset($_SESSION['session_user'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

define('INCLUDED', true);
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/category.php';
require_once __DIR__ . '/src/product.php';
require_once __DIR__ . '/incs/isAdmin.php';

$database = new Database();
$db = $database->getConnection();
checkAdmin($db);
$product = new Product($db);
$category = new Category($db);

$page_title = "Inserisci Prodotto";
$page_css = "css/create_product.css"; 
require_once __DIR__ . "/incs/header.php";

echo '<div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3"><a href="dashboard.php" class="btn btn-primary">Vedi prodotti</a></div>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product->name = htmlspecialchars(strip_tags($_POST['name']));
    $product->price = htmlspecialchars(strip_tags($_POST['price']));
    $product->description = htmlspecialchars(strip_tags($_POST['description']));
    $product->category_id = htmlspecialchars(strip_tags($_POST['category_id']));

    if ($product->create()) {
        echo "<div class='alert alert-success'>Il prodotto è stato creato con successo.</div>";
    } else {
        echo "<div class='alert alert-danger'>Il prodotto non è stato creato con successo.</div>";
    }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type='text' name='name' class='form-control' required />

        <label class="form-label">Prezzo</label>
        <input type='text' name='price' class='form-control' required />

        <label class="form-label">Descrizione</label>
        <textarea name='description' class='form-control'></textarea>

        <label class="form-label">Categoria</label>

        <?php
        $stmt = $category->read();

        echo '<select class="form-select" name="category_id" required>';
        echo "<option value=''>Select category...</option>";

        while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row_category);
            echo "<option value='{$id}'>{$name}</option>";
        }
        echo "</select>";
        ?>
    </div>
    <button type="submit" class="btn btn-primary">Inserisci Prodotto</button>
</form>

<?php
require_once __DIR__ . "/incs/footer.php";
?>
