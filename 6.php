<?php

function evaluatePostfix(string $expression): ?int {
    $tokens = explode(' ', $expression);
    $stack = [];
    
    foreach ($tokens as $token) {
        if (ctype_digit($token)) {
            array_push($stack, (int)$token);
        } elseif ($token === '+' || $token === '-' || $token === '*') {
            if (count($stack) < 2) {
                return null;
            }
            
            $b = array_pop($stack);
            $a = array_pop($stack);
            
            switch ($token) {
                case '+':
                    array_push($stack, $a + $b);
                    break;
                case '-':
                    array_push($stack, $a - $b);
                    break;
                case '*':
                    array_push($stack, $a * $b);
                    break;
            }
        } else {
            return null;
        }
    }
    
    if (count($stack) !== 1) {
        return null;
    }
    
    return array_pop($stack);
}

function getPostParameter(string $key): ?string {
    return isset($_POST[$key]) ? $_POST[$key] : null;
}

$result = null;
$error = null;
$expression = getPostParameter('expression');

if ($expression !== null) {
    $expression = trim($expression);
    
    if ($expression === '') {
        $error = 'Пожалуйста, введите выражение';
    } else {
        $value = evaluatePostfix($expression);
        
        if ($value === null) {
            $error = 'Некорректное выражение';
        } else {
            $result = $value;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Обратная польская запись</title>
    <style>
        .example {
            font-family: monospace;
            background-color: #f0f0f0;
            padding: 5px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <form method="post">
        <label for="expression">Введите выражение в постфиксной записи:</label><br>
        <input type="text" name="expression" id="expression" size="50" placeholder="8 9 + 1 7 - *" required><br>
        <small>Числа и операции отделяются пробелами. Допустимые операции: +, -, *</small><br>
        <button type="submit">Вычислить</button>
    </form>
    
    <div class="example">
        <strong>Пример:</strong> 8 9 + 1 7 - * = -102
    </div>
    
    <?php if ($error !== null): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($result !== null): ?>
        <p>Результат: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>