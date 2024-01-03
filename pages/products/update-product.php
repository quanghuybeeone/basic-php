<?php
require_once "../../db.php";

function displayError($message)
{
    echo "<p style='color: red;'>Error: $message</p>";
}

// Cập nhật người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    $product_id = $_POST['product_id'];
    $name_product = $_POST['name_product'];
    $descrision = $_POST['descrision'];
    $price = $_POST['price'];
    $imgUrl = $_POST['imgUrl'];

    try {
        $stmt = $conn->prepare("UPDATE products SET name_product = :name_product, descrision = :descrision, price = :price, imgUrl = :imgUrl WHERE id = :product_id");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':name_product', $name_product);
        $stmt->bindParam(':descrision', $descrision);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':imgUrl', $imgUrl);
        $stmt->execute();

        echo "<p>User updated successfully.</p>";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        displayError($e->getMessage());
    }
}
?>
<?php include_once('../../layout/header.php') ?>
<h1 class="text-center">Update Product</h1>
<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name_product" value="<?php echo $_GET['name_product']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="descrision" value="<?php echo $_GET['descrision']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="number" name="price" value="<?php echo $_GET['price']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Img Url</label>
            <input type="text" name="imgUrl" value="<?php echo $_GET['imgUrl']; ?>" class="form-control" required>
        </div>
        <input type="submit" name="update_user" value="Update" class="btn btn-warning text-light">
    </form>
</div>

<?php include_once('../../layout/footer.php') ?>