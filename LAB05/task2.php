<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Перевірка великих літер</title>
</head>
<body>
    <h2>Перевірка наявності великих літер у тексті</h2>
    
    <form method="post">
        <label for="text">Введіть текст:</label>
        <input type="text" name="text" required>
        <button type="submit">Перевірити</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $text = $_POST["text"];

        if (preg_match('/[A-ZА-ЯЁ]/u', $text)) {
            echo "<h3>Текст містить хоча б одну велику літеру.</h3>";
        } else {
            echo "<h3>Текст не містить великих літер.</h3>";
        }
    }
    ?>
</body>
</html>