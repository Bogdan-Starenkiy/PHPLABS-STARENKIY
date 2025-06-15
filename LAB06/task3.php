<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "StudentManagement";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS Students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    age INT,
    grade VARCHAR(10)
)");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $grade = $_POST['grade'] ?? '';

    if (!empty($name) && !empty($age) && !empty($grade)) {
        $stmt = $conn->prepare("INSERT INTO Students (name, age, grade) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $age, $grade);
        if ($stmt->execute()) {
            $message = "<p style='color:green;'>Студента додано успішно!</p>";
        } else {
            $message = "<p style='color:red;'>Помилка додавання: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p style='color:red;'>Усі поля обов'язкові!</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $delete_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM Students WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "<p style='color:green;'>Запис видалено!</p>";
    } else {
        $message = "<p style='color:red;'>Помилка видалення: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM Students ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список студентів</title>
</head>
<body>
    <h2>Додати нового студента</h2>
    <?= $message ?>
    <form method="POST" action="">
        <input type="hidden" name="add_student" value="1">
        <label>Ім’я:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Вік:</label><br>
        <input type="number" name="age" required><br><br>

        <label>Клас/Група:</label><br>
        <input type="text" name="grade" required><br><br>

        <input type="submit" value="Додати">
    </form>

    <h2>Список студентів</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Ім’я</th>
                <th>Вік</th>
                <th>Клас</th>
                <th>Дія</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['age']) ?></td>
                <td><?= htmlspecialchars($row['grade']) ?></td>
                <td>
                    <form method="POST" action="" onsubmit="return confirm('Ви впевнені, що хочете видалити цього студента?');">
                        <input type="hidden" name="delete_student" value="1">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <input type="submit" value="Видалити">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Немає жодного студента.</p>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>