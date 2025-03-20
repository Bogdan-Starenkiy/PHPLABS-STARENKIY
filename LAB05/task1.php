<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обчислення факторіала</title>
</head>
<body>
    <h2>Обчислення факторіала числа</h2>
    
    <form method="get">
        <label for="number">Введіть число:</label>
        <input type="number" name="number" min="0" required>
        <button type="submit">Обчислити</button>
    </form>

    <?php
    function factorial($n) {
        if ($n == 0 || $n == 1) {
            return 1;
        }
        return $n * factorial($n - 1);
    }

    if (isset($_GET["number"])) {
        $number = (int) $_GET["number"];
        if ($number < 0) {
            echo "<h3>Факторіал від'ємного числа не визначений.</h3>";
        } else {
            echo "<h3>Факторіал $number! = " . factorial($number) . "</h3>";
        }
    }
    ?>
</body>
</html>