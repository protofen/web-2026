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
$error = null;
$yearString = getPostParameter('year');

if ($yearString !== null && $yearString < 30000) {
    $year = (int)$yearString;
    if ($year <= 0) {
        $error = 'Год должен быть положительным числом больше 0';
    } else {
        $result = isLeapYear($year) ? 'YES' : 'NO';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>1</title>
    <style>
        .error {
            color: red;
        }
        input[type=number] {
            padding: 5px;
            margin: 5px 0;
        }
        button {
            padding: 5px 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form method="post">
        <label for="year">Введите год:</label>
        <input type="number", name="year", id="year", min="1", max="29999" , required>
        <button type="submit">Проверить</button>
    </form>
    
    <?php if ($error !== null): ?>
        <p class="error">Ошибка: <?php echo $error; ?></p>
    <?php elseif ($result !== null): ?>
        <p>Результат: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>