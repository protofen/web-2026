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
        'time' => '',
        'image' => 'post.png',
        'images' => ['post.png', 'post_1.png', 'post.png'],
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
        'time' => '1 час назад',
        'image' => 'post_1.png',
        'images' => ['post_1.png', 'post.png'],
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
        <a href="http://localhost/create_post.php">
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

    <?php foreach ($posts as $index => $post): ?>
        <div class="post-container <?= $index === 0 ? 'post-top' : 'post-bottom' ?>">
            
            <?php if ($index === 0 && isset($post['images']) && count($post['images']) > 1): ?>
                <div class="post-slider" data-post-id="<?= $post['id'] ?>">
                    <div class="slider-container">
                        <div class="slider-track" data-track>
                            <?php foreach ($post['images'] as $img): ?>
                                <div class="slider-slide">
                                    <img src="/src/images/<?= htmlspecialchars(trim($img)) ?>" alt="Post image" class="modal-trigger" data-img="<?= htmlspecialchars(trim($img)) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <button class="slider-btn slider-prev" data-prev>❮</button>
                        <button class="slider-btn slider-next" data-next>❯</button>
                        <div class="slider-indicator" data-indicator>
                            <span class="current">1</span>/<span class="total"><?= count($post['images']) ?></span>
                        </div>
                    </div>
                </div>
            
            <?php else: ?>
                <img src="/src/images/<?= htmlspecialchars($post['image']) ?>" alt="Post" class="post-image">
            <?php endif; ?>
            
        </div>

        <button class="like-button">
            <img src="/src/images/like.png" alt="Like" class="like-icon">
            <?= htmlspecialchars($post['likes']) ?>
        </button>

        <div class="post-text" data-full-text="<?= htmlspecialchars($post['text']) ?>">
        </div>
        <div class="post-time"><?= htmlspecialchars($post['time']) ?></div>
    <?php endforeach; ?>

    <div class="modal" id="imageModal">
        <button class="modal-close" id="modalClose">✕</button>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Full size image">
        </div>
    </div>

    <script src="src/js/home.js"></script>
</body>
</html>