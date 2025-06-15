<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StudentManagement";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
}
$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS Students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    age INT,
    grade VARCHAR(10)
)";
$conn->query($sql);

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $grade = $_POST['grade'] ?? '';

    if (!empty($name) && !empty($age) && !empty($grade)) {
        $stmt = $conn->prepare("INSERT INTO Students (name, age, grade) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $age, $grade);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>Студента успішно додано!</p>";
        } else {
            $message = "<p style='color:red;'>Помилка: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        $message = "<p style='color:red;'>Будь ласка, заповніть усі поля.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Система керування студентами</title>
</head>
<body>
    <h2>Додати нового студента</h2>
    <?= $message ?>
    <form method="POST" action="">
        <label>Ім’я:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Вік:</label><br>
        <input type="number" name="age" required><br><br>

        <label>Клас/Група:</label><br>
        <input type="text" name="grade" required><br><br>

        <input type="submit" value="Додати">
    </form>
</body>
</html>