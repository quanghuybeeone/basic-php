<?php include_once('layout/header.php') ?>



<?php
require_once('db.php');
function displayError($message)
{
    echo "<p style='color: red;'>Error: $message</p>";
}
?>
<div class="container pt-3">
    <h3 class="text-center">Danh sách sản phẩm</h3>
    
<?php
    try {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($products) > 0) {
            echo "<div class='row'>";

            foreach ($products as $product) {
                echo "
                <div class='col-3'>
                <div class='card' style='width: 18rem;'>
                    <img class='p-4' src='".$product['imgUrl']."' class='card-img-top' alt='...'>
                    <div class='card-body'>
                        <div class='d-flex justify-content-between'>
                            <h5 class='card-title'>".$product['name_product']."</h5>
                            <h6 class='card-title'>".$product['price']."đ</h6>
                        </div>
                        <p class='card-text'>".$product['descrision']."</p>
                        <div class='d-flex justify-content-center'>
                            <input type='number' id='quantity' value='1' min='1' hidden>
                            <a href='#' class='btn btn-primary add-to-cart' data-product-id='".$product['id']."'>Thêm vào giỏ hàng</a>
                        </div>
                    </div>
                </div>
            </div>
                    ";
            }

            echo "</div>";
        } else {
            echo "<p>No products found.</p>";
        }
    } catch (PDOException $e) {
        displayError($e->getMessage());
    }
    ?>


        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="add_to_cart.js"></script>
<?php include_once('layout/footer.php') ?>