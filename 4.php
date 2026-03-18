<?php

function isTicketLucky(string $ticket): bool {
    if (strlen($ticket) !== 6 || !ctype_digit($ticket)) {
        return false;
    }
    
    $firstSum = 0;
    $secondSum = 0;
    
    for ($i = 0; $i < 3; $i++) {
        $firstSum += (int)$ticket[$i];
    }
    
    for ($i = 3; $i < 6; $i++) {
        $secondSum += (int)$ticket[$i];
    }
    
    return $firstSum === $secondSum;
}

function getPostParameter(string $key): ?string {
    return isset($_POST[$key]) ? $_POST[$key] : null;
}

$luckyTickets = [];
$error = null;
$startTicket = getPostParameter('start_ticket');
$endTicket = getPostParameter('end_ticket');

if ($startTicket !== null && $endTicket !== null) {
    if (strlen($startTicket) !== 6 || strlen($endTicket) !== 6 || 
        !ctype_digit($startTicket) || !ctype_digit($endTicket)) {
        $error = 'Оба номера должны быть шестизначными числами';
    } else {
        $start = (int)$startTicket;
        $end = (int)$endTicket;
        
        if ($start > $end) {
            $error = 'Начальный номер должен быть меньше или равен конечному';
        } else {
            for ($i = $start; $i <= $end; $i++) {
                $ticket = str_pad((string)$i, 6, '0', STR_PAD_LEFT);
                
                if (isTicketLucky($ticket)) {
                    $luckyTickets[] = $ticket;
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>4</title>
    <style>
        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            max-height: 400px;
            overflow-y: auto;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <form method="post">
        <div>
            <label for="start_ticket">Начальный номер билета:</label>
            <input type="text" name="start_ticket" id="start_ticket" pattern="\d{6}" maxlength="6" required>
        </div>
        <div>
            <label for="end_ticket">Конечный номер билета:</label>
            <input type="text" name="end_ticket" id="end_ticket" pattern="\d{6}" maxlength="6" required>
        </div>
        <button type="submit">Найти счастливые билеты</button>
    </form>
    
    <?php if ($error !== null): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php elseif (!empty($luckyTickets)): ?>
        <div class="result">
            <h3>Найдено счастливых билетов: <?php echo count($luckyTickets); ?></h3>
            <?php foreach ($luckyTickets as $ticket): ?>
                <div><?php echo $ticket; ?></div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($startTicket !== null): ?>
        <p>Счастливых билетов не найдено</p>
    <?php endif; ?>
</body>
</html>