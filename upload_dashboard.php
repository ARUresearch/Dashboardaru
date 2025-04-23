<?php
$host = "localhost";
$user = "your_db_user";
$password = "your_db_pass";
$dbname = "aru_salary";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) die("DB Connection failed");

$category = $_POST['category_name'];
$details = $_POST['details'];
$uploadDir = "uploads/";
$imagePath = "";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imageName = basename($_FILES['image']['name']);
    $targetPath = $uploadDir . time() . "_" . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imagePath = $targetPath;
    } else {
        echo "Image upload failed!";
        exit;
    }
} else {
    echo "Invalid image file!";
    exit;
}

$sql = "INSERT INTO salary_categories (category_name, details, image_path)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $category, $details, $imagePath);

if ($stmt->execute()) {
    echo "Dashboard category inserted successfully!";
} else {
    echo "Database insert failed: " . $conn->error;
}

$conn->close();
?>
