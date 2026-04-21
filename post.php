<?php
$postId = isset($_GET['postId']) ? (int)$_GET['postId'] : 0;
$post = [
    'id' => 1,
    'title' => 'The Road Ahead',
    'subtitle' => 'Путь вперед',
    'author' => 'Ваня Денисов',
    'author_avatar' => 'pfp.png',
    'likes' => 203,
    'text' => 'Так красиво сегодня на улице! Настоящая зима)) Вспоминается Бродский: «Поздно ночью, в...',
    'time' => '2 часа назад',
    'image' => 'post.png',
    'full_text' => 'Так красиво сегодня на улице! Настоящая зима)) Вспоминается Бродский: «Поздно ночью, в пустой комнате, на белой простыне...»',
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?> | Пост</title>
    <link rel="stylesheet" href="src/css/home.css">
    <style>
        .post-page {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .post-page .post-title {
            font-family: Golos-UI_Bold;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .post-page .post-subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }
        .post-page .post-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .post-page .post-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .post-page .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 5px;
        }
        .post-page .author-name {
            font-family: Golos-UI_Bold;
            font-size: 16px;
        }
        .post-page .post-time {
            color: #bababa;
            font-size: 14px;
        }
        .post-page .post-image-full {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .post-page .post-full-text {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .post-page .like-button {
            position: static;
            margin-bottom: 30px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #222;
            text-decoration: none;
            font-family: Golos-UI_Regular;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="icons">
        <img src="/src/images/home.png" alt="Home">
        <img src="/src/images/dot.png" alt="Menu" class="icon-dot">
        <img src="/src/images/user.png" alt="Profile" class="icon-user">
        <img src="/src/images/plus.png" alt="Add" class="icon-plus">
    </div>

    <div class="post-page">
        <a href="http://localhost/home.php" class="back-link">← Назад к ленте</a>
        
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="post-subtitle"><?= htmlspecialchars($post['subtitle']) ?></div>
        
        <div class="post-meta">
            <div class="post-author">
                <img src="/src/images/<?= htmlspecialchars($post['author_avatar']) ?>" alt="Avatar" class="author-avatar">
                <span class="author-name"><?= htmlspecialchars($post['author']) ?></span>
            </div>
            <div class="post-time"><?= htmlspecialchars($post['time']) ?></div>
        </div>
        
        <img src="/src/images/<?= htmlspecialchars($post['image']) ?>" alt="Post" class="post-image-full">
        
        <div class="post-full-text">
            <?= nl2br(htmlspecialchars($post['full_text'])) ?>
        </div>
        
        <button class="like-button">
            <img src="/src/images/like.png" alt="Like" class="like-icon">
            <?= htmlspecialchars($post['likes']) ?>
        </button>
        
        <div class="post-id-info" style="margin-top: 20px; font-size: 12px; color: #bababa;">
            ID поста: <?= htmlspecialchars($postId) ?>
        </div>
    </div>
</body>
</html>