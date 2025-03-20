<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Перевірка від’ємного числа</title>
</head>
<body>
    <h2>Перевірка, чи є число від'ємним</h2>
    <form method="post">
        <label for="number">Введіть число:</label>
        <input type="number" step="any" name="number" required>
        <button type="submit">Перевірити</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $number = (float) $_POST["number"];

        if ($number < 0) {
            echo "<h3>Число $number є від'ємним.</h3>";
        } else {
            echo "<h3>Число $number не є від'ємним.</h3>";
        }
    }
    ?>
</body>
</html>