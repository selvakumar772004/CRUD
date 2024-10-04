// update.php
<?php
include 'config.php';

$id = $_GET['id'];
$name = $email = $password = "";
$errors = array();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
        $errors['name'] = "Name is required";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["email"])) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["password"])) {
        $password = "";
    } elseif (strlen($_POST["password"]) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    } else {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $id);
        }

        if ($stmt->execute()) {
            echo "Record updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<form method="post" action="">
    Name: <input type="text" name="name" value="<?php echo $name; ?>">
    <span><?php echo $errors['name'] ?? ''; ?></span><br>
    
    Email: <input type="text" name="email" value="<?php echo $email; ?>">
    <span><?php echo $errors['email'] ?? ''; ?></span><br>
    
    Password: <input type="password" name="password">
    <span><?php echo $errors['password'] ?? ''; ?></span><br>
    
    <input type="submit" value="Update">
</form>
