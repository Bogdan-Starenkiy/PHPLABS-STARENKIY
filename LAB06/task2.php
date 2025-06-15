<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $grade = $_POST['grade'] ?? '';

    if (!empty($name) && !empty($age) && !empty($grade)) {
        $conn = new mysqli("localhost", "root", "", "StudentManagement");

        if ($conn->connect_error) {
            die("Помилка з'єднання: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO Students (name, age, grade) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $age, $grade);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Студента додано успішно!</p>";
        } else {
            echo "<p style='color: red;'>Помилка додавання: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<p style='color: red;'>Будь ласка, заповніть всі поля.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати студента</title>
</head>
<body>
    <h2>Додати нового студента</h2>
    <form method="POST" action="add_student.php">
        <label>Ім’я студента:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Вік:</label><br>
        <input type="number" name="age" required><br><br>

        <label>Клас/група:</label><br>
        <input type="text" name="grade" required><br><br>

        <input type="submit" value="Додати">
    </form>
</body>
</html>