<?php
$numbers = [];
for ($i = 0; $i < 10; $i++) {
    $numbers[] = rand(1, 100);
}

$maxValue = max($numbers);
$minValue = min($numbers);

echo "Згенерований масив: " . implode(", ", $numbers) . "<br>";
echo "Максимальне значення: $maxValue<br>";
echo "Мінімальне значення: $minValue<br>";
?>