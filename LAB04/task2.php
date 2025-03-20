<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Конвертація температури</title>
</head>
<body>
    <h2>Конвертація з Цельсія у Фаренгейт</h2>
    
    <form method="post">
        <label for="celsius">Введіть температуру (°C):</label>
        <input type="number" step="any" name="celsius" required>
        <button type="submit">Конвертувати</button>
    </form>

    <?php
    function celsiusToFahrenheit($celsius) {
        return ($celsius * 9/5) + 32;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $celsius = (float) $_POST["celsius"];
        $fahrenheit = celsiusToFahrenheit($celsius);
        echo "<h3>$celsius °C = $fahrenheit °F</h3>";
    }
    ?>
</body>
</html>