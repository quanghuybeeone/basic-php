<?php
require_once "../../db.php";

function displayError($message)
{
    echo "<p style='color: red;'>Error: $message</p>";
}

// Cập nhật người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    $user_id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    try {
        $stmt = $conn->prepare("UPDATE users SET fullname = :fullname, email = :email WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
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
<h1 class="text-center">Update User</h1>
<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="fullname" value="<?php echo $_GET['fullname']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" value="<?php echo $_GET['email']; ?>" class="form-control" required>
        </div>
        <input type="submit" name="update_user" value="Update" class="btn btn-warning text-light">
    </form>
</div>

<?php include_once('../../layout/footer.php') ?>