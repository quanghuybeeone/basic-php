<?php include_once('../../layout/header.php') ?>
<h1 class="text-center">Quản lý Sản phẩm</h1>
<div class="container">
    <?php
    require_once "../../db.php";

    function displayError($message)
    {
        echo "<p style='color: red;'>Error: $message</p>";
    }

    // Thêm sản phẩm
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"]) && $_POST["name_product"] != '' && $_POST["descrision"] != '' && $_POST["price"] != '' && $_POST["imgUrl"] != '') {
        $name_product = $_POST['name_product'];
        $descrision = $_POST['descrision'];
        $price = $_POST['price'];
        $imgUrl = $_POST['imgUrl'];

        try {
            $stmt = $conn->prepare("INSERT INTO products (name_product, descrision, price, imgUrl) VALUES (:name_product, :descrision, :price, :imgUrl)");
            $stmt->bindParam(':name_product', $name_product);
            $stmt->bindParam(':descrision', $descrision);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':imgUrl', $imgUrl);
            $stmt->execute();

            echo "<p>Product added successfully.</p>";
        } catch (PDOException $e) {
            displayError($e->getMessage());
        }
    }

    // Xóa sản phẩm
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
        $product_id = $_POST['product_id'];

        try {
            $stmt = $conn->prepare("DELETE FROM products WHERE id = :product_id");

            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            echo "<p>Product deleted successfully.</p>";
        } catch (PDOException $e) {
            displayError($e->getMessage());
        }
    }
    ?>

    <h2>Add Product</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-5">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name_product" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Descrision</label>
            <input type="text" name="descrision" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Url Img</label>
            <input type="text" name="imgUrl" class="form-control">
        </div>
        <input class="btn btn-primary" type="submit" name="add_product" value="Add Product">
    </form>
    <h2>View Products</h2>

    <?php
    try {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($products) > 0) {
            echo "<table class='table table-striped table-hover'>";
            echo "
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Img</th>
                            <th>Name</th>
                            <th>Descrision</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                ";

            foreach ($products as $product) {
                echo "
                        <tr>
                            <td>" . $product['id'] . "</td>
                            <td><img width='150px' src='" . $product['imgUrl'] . "' alt=''></td>
                            
                            <td>" . $product['name_product'] . "</td>
                            <td>" . $product['descrision'] . "</td>
                            <td>" . $product['price'] . " đ</td>
                            <td>
                                <form action='" . $_SERVER['PHP_SELF'] . "' method='post' style='display: inline-block;'>
                                    <input type='hidden' name='product_id' value='" . $product['id'] . "'>
                                    <input type='submit' name='delete_product' value='Delete' class='btn btn-danger'>
                                </form>
                                <form action='update-product.php' method='get' style='display: inline-block;'>
                                    <input type='hidden' name='product_id' value='" . $product['id'] . "'>
                                    <input type='text' name='name_product' value='" . $product['name_product'] . "' hidden>
                                    <input type='text' name='descrision' value='" . $product['descrision'] . "' hidden>
                                    <input type='number' name='price' value='" . $product['price'] . "' hidden>
                                    <input type='text' name='imgUrl' value='" . $product['imgUrl'] . "' hidden>
                                    <input type='submit' name='update_product' value='Update' class='btn btn-warning text-light'>
                                </form>
                            </td>
                        </tr>
                    ";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No products found.</p>";
        }
    } catch (PDOException $e) {
        displayError($e->getMessage());
    }
    ?>
</div>
<?php include_once('../../layout/footer.php') ?>
