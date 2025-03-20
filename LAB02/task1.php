<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обчислення відстані</title>
</head>
<body>
    <h2>Обчислення відстані між двома точками</h2>
    <form method="post">
        <label for="x1">X1:</label>
        <input type="number" step="any" name="x1" required>
        <label for="y1">Y1:</label>
        <input type="number" step="any" name="y1" required><br><br>
        
        <label for="x2">X2:</label>
        <input type="number" step="any" name="x2" required>
        <label for="y2">Y2:</label>
        <input type="number" step="any" name="y2" required><br><br>
        
        <button type="submit">Обчислити</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $x1 = $_POST["x1"];
        $y1 = $_POST["y1"];
        $x2 = $_POST["x2"];
        $y2 = $_POST["y2"];

        function distance($x1, $y1, $x2, $y2) {
            return sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
        }

        $dist = distance($x1, $y1, $x2, $y2);
        echo "<h3>Відстань між точками: " . number_format($dist, 2) . "</h3>";
    }
    ?>
</body>
</html>