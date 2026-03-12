<?php

function isLeapYear(int $year): bool {
    if ($year % 400 === 0) {
        return true;
    }
    
    if ($year % 100 === 0) {
        return false;
    }
    
    return $year % 4 === 0;
}

function getPostParameter(string $key): ?string {
    return isset($_POST[$key]) ? $_POST[$key] : null;
}

$result = null;
$yearString = getPostParameter('year');

if ($yearString !== null) {
    $year = (int)$yearString;
    $result = isLeapYear($year) ? 'YES' : 'NO';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Високосный год</title>
</head>
<body>
    <form method="post">
        <label for="year">Введите год:</label>
        <input type="number" name="year" id="year" required>
        <button type="submit">Проверить</button>
    </form>
    
    <?php if ($result !== null): ?>
        <p>Результат: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>