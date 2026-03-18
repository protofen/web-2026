<?php

function factorial(int $n): int {
    if ($n <= 1) {
        return 1;
    }
    
    return $n * factorial($n - 1);
}

function getPostParameter(string $key): ?string {
    return isset($_POST[$key]) ? $_POST[$key] : null;
}

$result = null;
$error = null;
$numberString = getPostParameter('number');

if ($numberString !== null) {
    if (!ctype_digit($numberString)) {
        $error = 'Пожалуйста, введите целое неотрицательное число';
    } else {
        $number = (int)$numberString;
        
        if ($number < 0) {
            $error = 'Факториал определен только для неотрицательных чисел';
        } elseif ($number > 21) {
            $error = 'Число слишком большое';
        } else {
            $result = factorial($number);
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>5</title>
</head>
<body>
    <form method="post">
        <label for="number">Введите число:</label>
        <input type="number" name="number" id="number" min="0" max="20" required>
        <button type="submit">Вычислить факториал</button>
    </form>
    
    <?php if ($error !== null): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($result !== null): ?>
        <p>Факториал: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>