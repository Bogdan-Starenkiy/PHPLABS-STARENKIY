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
    echo "Базу даних створено успішно<br>";
} else {
    echo "Помилка створення бази даних: " . $conn->error;
}

$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS Students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    age INT,
    grade VARCHAR(10)
)";
if ($conn->query($sql) === TRUE) {
    echo "Таблицю створено успішно<br>";
} else {
    echo "Помилка створення таблиці: " . $conn->error;
}

$sql = "INSERT INTO Students (name, age, grade) VALUES 
('Іван Іванов', 16, '10-A'),
('Марія Петренко', 17, '11-B'),
('Олег Коваль', 15, '9-C')";
if ($conn->query($sql) === TRUE) {
    echo "Дані успішно додані<br>";
} else {
    echo "Помилка додавання даних: " . $conn->error;
}

$conn->close();
?>