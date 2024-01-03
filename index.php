<!DOCTYPE html>
<html>

<head>
    <title>CRUD User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <h1 class="text-center">CRUD User</h1>
    <div class="container">
    <?php
    require_once "db.php";

    function displayError($message)
    {
        echo "<p style='color: red;'>Error: $message</p>";
    }

    // Thêm người dùng
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_user"]) && $_POST["fullname"] != '' && $_POST["email"] != '') {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];

        try {
            $stmt = $conn->prepare("INSERT INTO users (fullname, email) VALUES (:fullname, :email)");
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            echo "<p>User added successfully.</p>";
        } catch (PDOException $e) {
            displayError($e->getMessage());
        }
    }

    // Xóa người dùng
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
        $user_id = $_POST['user_id'];

        try {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = :user_id");
            
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            echo "<p>User deleted successfully.</p>";
        } catch (PDOException $e) {
            displayError($e->getMessage());
        }
    }
    ?>
    
        <h2>Add User</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-5">
            <div class="mb-3">
                <label for="exampleInputFullname1" class="form-label">Full name</label>
                <input type="text" name="fullname" class="form-control" id="exampleInputFullname1">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <input class="btn btn-primary" type="submit" name="add_user" value="Add User">
        </form>
        <h2>View Users</h2>

        <?php
        try {
            $stmt = $conn->prepare("SELECT * FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            if (count($users) > 0) {
                echo "<table class='table table-striped table-hover'>";
                echo "
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                ";
        
                foreach ($users as $user) {
                    echo "
                        <tr>
                            <td>" . $user['id'] . "</td>
                            <td>" . $user['fullname'] . "</td>
                            <td>" . $user['email'] . "</td>
                            <td>
                                <form action='" . $_SERVER['PHP_SELF'] . "' method='post' style='display: inline-block;'>
                                    <input type='hidden' name='user_id' value='" . $user['id'] . "'>
                                    <input type='submit' name='delete_user' value='Delete' class='btn btn-danger'>
                                </form>
                                <form action='update-user.php' method='get' style='display: inline-block;'>
                                    <input type='hidden' name='user_id' value='" . $user['id'] . "'>
                                    <input type='text' name='fullname' value='" . $user['fullname'] . "' hidden>
                                    <input type='email' name='email' value='" . $user['email'] . "' hidden>
                                    <input type='submit' name='update_user' value='Update' class='btn btn-warning text-light'>
                                </form>
                            </td>
                        </tr>
                    ";
                }
        
                echo "</tbody></table>";
            } else {
                echo "<p>No users found.</p>";
            }
        } catch (PDOException $e) {
            displayError($e->getMessage());
        }
        ?>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>