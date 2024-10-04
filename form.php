// form.php
<?php
include 'config.php';

$name = $email = $password = "";
$errors = array();

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
        $errors['password'] = "Password is required";
    } elseif (strlen($_POST["password"]) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    } else {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "New record created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Name: <input type="text" name="name" value="<?php echo $name; ?>">
    <span><?php echo $errors['name'] ?? ''; ?></span><br>
    
    Email: <input type="text" name="email" value="<?php echo $email; ?>">
    <span><?php echo $errors['email'] ?? ''; ?></span><br>
    
    Password: <input type="password" name="password">
    <span><?php echo $errors['password'] ?? ''; ?></span><br>
    
    <input type="submit" value="Submit">
</form>
