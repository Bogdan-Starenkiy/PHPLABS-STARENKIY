<?php
// Підключення до MySQL
$mysqli = new mysqli("localhost", "root", "");
if ($mysqli->connect_error) {
    die("Помилка з'єднання: " . $mysqli->connect_error);
}

// Створення бази даних
$mysqli->query("CREATE DATABASE IF NOT EXISTS BookStore");
$mysqli->select_db("BookStore");

// Створення таблиць
$mysqli->query("CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)");
$mysqli->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
)");
$mysqli->query("CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT,
    price DECIMAL(10,2),
    FOREIGN KEY (author_id) REFERENCES authors(id)
)");
$mysqli->query("CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    order_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
)");

// Додавання мінімальних тестових записів, якщо таблиці порожні
if ($mysqli->query("SELECT COUNT(*) AS c FROM authors")->fetch_assoc()['c'] == 0) {
    $mysqli->query("INSERT INTO authors (name) VALUES ('Тарас Шевченко')");
}
if ($mysqli->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'] == 0) {
    $mysqli->query("INSERT INTO users (username, email) VALUES ('testuser', 'test@example.com')");
}
if ($mysqli->query("SELECT COUNT(*) AS c FROM books")->fetch_assoc()['c'] == 0) {
    $mysqli->query("INSERT INTO books (title, author_id, price) VALUES ('Кобзар', 1, 120.50)");
}
if ($mysqli->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'] == 0) {
    $mysqli->query("INSERT INTO orders (user_id, book_id, order_date) VALUES (1, 1, CURDATE())");
}

// Додавання нової книги
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $stmt = $mysqli->prepare("INSERT INTO books (title, author_id, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $_POST['title'], $_POST['author_id'], $_POST['price']);
    $stmt->execute();
    header("Location: task1.php");
    exit;
}

// Видалення книги
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM books WHERE id = $id");
    header("Location: task1.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Книгарня</title>
</head>
<body>
    <h2>Список книг</h2>
    <table border="1">
        <tr><th>ID</th><th>Назва</th><th>Автор</th><th>Ціна</th><th>Дії</th></tr>
        <?php
        $result = $mysqli->query("SELECT books.id, books.title, books.price, authors.name AS author 
                                  FROM books 
                                  LEFT JOIN authors ON books.author_id = authors.id");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['author']}</td>
                <td>{$row['price']}</td>
                <td><a href='?delete={$row['id']}'>Видалити</a></td>
            </tr>";
        }
        ?>
    </table>

    <h2>Додати книгу</h2>
    <form method="POST">
        Назва: <input type="text" name="title" required><br>
        Автор:
        <select name="author_id">
            <?php
            $authors = $mysqli->query("SELECT * FROM authors");
            while ($author = $authors->fetch_assoc()) {
                echo "<option value='{$author['id']}'>{$author['name']}</option>";
            }
            ?>
        </select><br>
        Ціна: <input type="number" step="0.01" name="price" required><br>
        <input type="submit" name="add_book" value="Додати книгу">
    </form>

    <h2>Найпопулярніші книги</h2>
    <table border="1">
        <tr><th>Назва</th><th>Кількість замовлень</th></tr>
        <?php
        $popular = $mysqli->query("
            SELECT books.title, COUNT(orders.id) AS total_orders
            FROM orders
            JOIN books ON orders.book_id = books.id
            GROUP BY books.id
            ORDER BY total_orders DESC
            LIMIT 5
        ");
        while ($row = $popular->fetch_assoc()) {
            echo "<tr><td>{$row['title']}</td><td>{$row['total_orders']}</td></tr>";
        }
        ?>
    </table>
</body>
</html>