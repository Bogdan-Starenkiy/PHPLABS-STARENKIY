<?php
// Підключення
$mysqli = new mysqli("localhost", "root", "");
if ($mysqli->connect_error) {
    die("Помилка з'єднання: " . $mysqli->connect_error);
}
$mysqli->query("CREATE DATABASE IF NOT EXISTS BookStore");
$mysqli->select_db("BookStore");

// Таблиці
$mysqli->query("CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
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

// Початкові дані
if ($mysqli->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'] == 0) {
    $mysqli->query("INSERT INTO users (username, email) VALUES ('testuser', 'test@example.com')");
}

// Функція для отримання або створення автора
function get_or_create_author_id($mysqli, $name) {
    $stmt = $mysqli->prepare("SELECT id FROM authors WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    } else {
        $stmt = $mysqli->prepare("INSERT INTO authors (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $mysqli->insert_id;
    }
}

// Додавання книги
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $author_id = get_or_create_author_id($mysqli, trim($_POST['author_name']));
    $stmt = $mysqli->prepare("INSERT INTO books (title, author_id, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $_POST['title'], $author_id, $_POST['price']);
    $stmt->execute();
    header("Location: task1.php");
    exit;
}

// Оновлення книги
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_book'])) {
    $author_id = get_or_create_author_id($mysqli, trim($_POST['author_name']));
    $stmt = $mysqli->prepare("UPDATE books SET title=?, author_id=?, price=? WHERE id=?");
    $stmt->bind_param("sidi", $_POST['title'], $author_id, $_POST['price'], $_POST['id']);
    $stmt->execute();
    header("Location: task1.php");
    exit;
}

// Видалення
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM books WHERE id = $id");
    header("Location: task1.php");
    exit;
}

// Отримання книги для редагування
$editBook = null;
$editAuthorName = "";
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editBook = $mysqli->query("SELECT * FROM books WHERE id = $id")->fetch_assoc();
    if ($editBook) {
        $res = $mysqli->query("SELECT name FROM authors WHERE id = {$editBook['author_id']}");
        $editAuthorName = $res->fetch_assoc()['name'] ?? '';
    }
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
                <td>
                    <a href='?edit={$row['id']}'>Редагувати</a> |
                    <a href='?delete={$row['id']}'>Видалити</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <h2><?= $editBook ? "Редагування книги" : "Додати книгу" ?></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $editBook['id'] ?? '' ?>">
        Назва: <input type="text" name="title" value="<?= $editBook['title'] ?? '' ?>" required><br>
        Автор (введіть ім'я): <input type="text" name="author_name" value="<?= $editAuthorName ?>" required><br>
        Ціна: <input type="number" step="0.01" name="price" value="<?= $editBook['price'] ?? '' ?>" required><br>
        <input type="submit" name="<?= $editBook ? "update_book" : "add_book" ?>" value="<?= $editBook ? "Оновити" : "Додати" ?>">
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