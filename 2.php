<?php

function digitToWord(string $digit): ?string {
    $words = [
        '0' => 'Ноль',
        '1' => 'Один',
        '2' => 'Два',
        '3' => 'Три',
        '4' => 'Четыре',
        '5' => 'Пять',
        '6' => 'Шесть',
        '7' => 'Семь',
        '8' => 'Восемь',
        '9' => 'Девять',
    ];
    
    return isset($words[$digit]) ? $words[$digit] : null;
}

function getPostParameter(string $key): ?string {
    return isset($_POST[$key]) ? $_POST[$key] : null;
}

$result = null;
$error = null;
$digitString = getPostParameter('digit');

if ($digitString !== null) {
    if (strlen($digitString) === 1 && ctype_digit($digitString)) {
        $result = digitToWord($digitString);
    } else {
        $error = 'Пожалуйста, введите одну цифру от 0 до 9';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Цифра в слово</title>
</head>
<body>
    <form method="post">
        <label for="digit">Введите цифру (0-9):</label>
        <input type="text" name="digit" id="digit" maxlength="1" required>
        <button type="submit">Преобразовать</button>
    </form>
    
    <?php if ($error !== null): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($result !== null): ?>
        <p>Результат: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>