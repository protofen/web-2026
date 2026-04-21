<?php
$posts = [
    [
        'id' => 1,
        'title' => 'The Road Ahead',
        'subtitle' => 'Путешествия и открытия',
        'img_modifier' => 'road',
        'author' => 'Ваня Денисов',
        'author_avatar' => 'pfp.png',
        'likes' => 203,
        'text' => 'Так красиво сегодня на улице! Настоящая зима)) Вспоминается Бродский: «Поздно ночью, в...',
        'time' => '2 часа назад',
        'image' => 'post.png',
    ],
    [
        'id' => 2,
        'title' => 'Flowers',
        'subtitle' => 'Цветы',
        'img_modifier' => 'flowers',
        'author' => 'Лиза Дёмина',
        'author_avatar' => 'pfp_1.png',
        'likes' => 203,
        'text' => '',
        'time' => '',
        'image' => 'post_1.png',
    ],
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лента</title>
    <link rel="stylesheet" href="src/css/home.css">
</head>
<body>
    <div class="icons">
        <a href="http://localhost/home.php">
            <img src="/src/images/home.png" alt="Home">
        </a>
        <img src="/src/images/dot.png" alt="Menu" class="icon-dot">
        <a href="http://localhost/profile.html">
            <img src="/src/images/user.png" alt="Profile" class="icon-user">
        </a>
        <a href="">
            <img src="/src/images/plus.png" alt="Add" class="icon-plus">
        </a>
        </div>
    <div class="container">
        <?php foreach ($posts as $index => $post): ?>
            <?php if ($index === 0): ?>
                <div class="profile-card profile-top">
                    <div class="profile-avatar">
                        <a href="http://localhost/profile.html">
                            <img src="/src/images/<?= htmlspecialchars($post['author_avatar']) ?>" alt="Avatar" class="avatar-img">
                        </a>
                    </div>
                    <div class="profile-name"><?= htmlspecialchars($post['author']) ?></div>
                    <img src="/src/images/edit.png" alt="Edit" class="profile-edit">
                </div>
            <?php else: ?>
                <div class="profile-card profile-bottom">
                    <div class="profile-avatar">
                        <img src="/src/images/<?= htmlspecialchars($post['author_avatar']) ?>" alt="Avatar" class="avatar-img">
                    </div>
                    <div class="profile-name"><?= htmlspecialchars($post['author']) ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <?php
    foreach ($posts as $index => $post):
        include 'post_preview.php';
    endforeach;
    ?>
</body>
</html>