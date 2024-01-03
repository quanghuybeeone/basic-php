<?php
session_start();
require_once('db.php');

if (isset($_POST['productId']) && isset($_POST['quantity'])) {
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];

    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name_product'],
                'img' => $product['imgUrl'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];

            echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được thêm vào giỏ hàng.', 'products' => $_SESSION['cart']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} 

if(isset($_POST['renderCart'])){
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    echo json_encode(['products' => $_SESSION['cart']]);
}
?>