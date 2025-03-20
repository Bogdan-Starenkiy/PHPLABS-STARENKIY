<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблиця множення до 5</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            text-align: center;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Таблиця множення до 5</h2>
    <table>
        <tr>
            <th>x</th>
            <?php
            for ($i = 1; $i <= 5; $i++) {
                echo "<th>$i</th>";
            }
            ?>
        </tr>
        <?php
        for ($i = 1; $i <= 5; $i++) {
            echo "<tr><th>$i</th>";
            for ($j = 1; $j <= 5; $j++) {
                echo "<td>" . ($i * $j) . "</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>