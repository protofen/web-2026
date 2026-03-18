<?php

function parseDate(string $input): ?array {
    $patterns = [
        '/^(\d{1,2})[.\-\/](\d{1,2})[.\-\/](\d{4})$/',
        '/^(\d{4})[.\-\/](\d{1,2})[.\-\/](\d{1,2})$/',
        '/^(\d{1,2})[.\-\/](\d{1,2})[.\-\/](\d{2})$/'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, trim($input), $matches)) {
            if (count($matches) == 4) {
                if (is_numeric($matches[1]) && is_numeric($matches[2]) && is_numeric($matches[3])) {
                    if (strlen($matches[3]) == 4) {
                        return [(int)$matches[1], (int)$matches[2], (int)$matches[3]];
                    } elseif (strlen($matches[1]) == 4) {
                        return [(int)$matches[3], (int)$matches[2], (int)$matches[1]];
                    }
                }
            }
        }
    }
    
    return null;
}

function getZodiacSign(int $day, int $month): ?string {
    if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
        return 'Овен';
    }
    if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
        return 'Телец';
    }
    if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
        return 'Близнецы';
    }
    if (($month == 6 && $day >= 22) || ($month == 7 && $day <= 22)) {
        return 'Рак';
    }
    if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
        return 'Лев';
    }
    if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
        return 'Дева';
    }
    if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
        return 'Весы';
    }
    if (($month == 10 && $day >= 24) || ($month == 11 && $day <= 22)) {
        return 'Скорпион';
    }
    if (($month == 11 && $day >= 23) || ($month == 12 && $day <= 21)) {
        return 'Стрелец';
    }
    if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 20)) {
        return 'Козерог';
    }
    if (($month == 1 && $day >= 21) || ($month == 2 && $day <= 20)) {
        return 'Водолей';
    }
    if (($month == 2 && $day >= 21) || ($month == 3 && $day <= 20)) {
        return 'Рыбы';
    }
    
    return null;
}

function getPostParameter(string $key): ?string {
    return isset($_POST[$key]) ? $_POST[$key] : null;
}

$result = null;
$error = null;
$dateString = getPostParameter('date');

if ($dateString !== null) {
    $parsedDate = parseDate($dateString);
    
    if ($parsedDate === null) {
        $error = 'Не удалось распознать формат даты';
    } else {
        list($day, $month, $year) = $parsedDate;
        
        if ($day < 1 || $day > 31 || $month < 1 || $month > 12 || $year < 1) {
            $error = 'Введите корректную дату';
        } else {
            $zodiac = getZodiacSign($day, $month);
            
            if ($zodiac === null) {
                $error = 'Не удалось определить знак зодиака для указанной даты';
            } else {
                $result = $zodiac;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>3</title>
</head>
<body>
    <form method="post">
        <label for="date">Введите дату (в любом формате):</label>
        <input type="text" name="date" id="date" placeholder="15.04.1452" required>
        <button type="submit">Узнать знак зодиака</button>
    </form>
    
    <?php if ($error !== null): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif ($result !== null): ?>
        <p>Знак зодиака: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>