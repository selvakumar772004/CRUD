// delete.php
<?php
include 'config.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Record deleted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>
