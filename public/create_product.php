<?php
include_once '../php-inc/database.php';
include_once '../php-inc/product.php';
include_once '../php-inc/category.php';

if (!defined('INCLUDED')) {
  http_response_code(404);
  include '../status-pages/404.html';
  exit();
}
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);
$page_title = "Inserisci Prodotto";
$page_css = "../css/create_product.css";
include_once "../php-inc/header.php";

echo '<div class="d-grid gap-2 d-md-flex justify-content-md-end"><a href="./dashboard.php" class="btn btn-primary">Vedi prodotti</a></div>';

?>

	<?php
	if($_POST){

	    $product->name = $_POST['name'];
	    $product->price = $_POST['price'];
	    $product->description = $_POST['description'];
	    $product->category_id = $_POST['category_id'];

	    if($product->create()){
	        echo "<div class='alert alert-success'>Il prodotto è stato creato con successo.</div>";
	    }

	    else{
	        echo "<div class='alert alert-danger'>Il prodotto non è stato creato con successo.</div>";
	    }
	}
	?>


		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

		    <div class="mb-3">

		            <label class="form-label">Nome</label>
		            <input type='text' name='name' class='form-control' />

		            <label class="form-label">Prezzo</label>
		            <input type='text' name='price' class='form-control' />

		            <label class="form-label">Descrizione</label>
		            <textarea name='description' class='form-control'></textarea>

		            <label class="form-label">Categoria</label>

					<?php
					$stmt = $category->read();

					echo '<select class="form-select" name="category_id">';
					    echo "<option>Select category...</option>";

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
include_once "../php-inc/footer.php";
?>
