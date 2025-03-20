<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Перетворення чисел у PHP</title>
</head>
<body>
    <h2>Перетворення цілих і дробових чисел</h2>
    <form method="post">
        <label for="integer">Введіть ціле число:</label>
        <input type="number" name="integer" required><br><br>
        
        <label for="float">Введіть дробове число:</label>
        <input type="number" step="any" name="float" required><br><br>
        
        <button type="submit">Обчислити</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $integerValue = (int) $_POST["integer"];
        $floatValue = (float) $_POST["float"];

        echo "<h3>Введені значення:</h3>";
        echo "Ціле число: $integerValue (тип: " . gettype($integerValue) . ")<br>";
        echo "Дробове число: $floatValue (тип: " . gettype($floatValue) . ")<br>";

        $intToFloat = (float) $integerValue;
        $floatToInt = (int) $floatValue;

        echo "<h3>Після перетворення:</h3>";
        echo "int → float: $intToFloat (тип: " . gettype($intToFloat) . ")<br>";
        echo "float → int: $floatToInt (тип: " . gettype($floatToInt) . ")<br>";
    }
    ?>
</body>
</html>