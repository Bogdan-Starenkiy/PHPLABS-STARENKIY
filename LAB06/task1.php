<?php $servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "hotel_management";
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) 
{ 
    die("Помилка з'єднання: " . $conn->connect_error); 
}
$sql = "CREATE DATABASE IF NOT EXISTS $dbname"; 
if ($conn->query($sql) === TRUE) { 
    echo "База даних створена успішно<br>"; 
} else 
{ echo "Помилка створення бази даних: " . $conn->error; }
$conn->select_db($dbname); 
$sql = "CREATE TABLE IF NOT EXISTS rooms ( 
id INT AUTO_INCREMENT PRIMARY KEY, 
room_number VARCHAR(10), 
room_type VARCHAR(50), 
price FLOAT )"; 
if ($conn->query($sql) === TRUE) { 
    echo "Таблиця створена успішно<br>"; } 
    else {
    echo "Помилка створення таблиці: " . $conn->error; }
$sql = "INSERT INTO rooms (room_number, room_type, price) VALUES 
('101', 'Single', 50.00), 
('102', 'Double', 80.00), 
('103', 'Suite', 150.00)"; 
if ($conn->query($sql) === TRUE) { 
    echo "Дані успішно додані<br>"; } 
    else { echo "Помилка додавання даних: " . $conn->error; 
    }
$conn->close(); 
?>