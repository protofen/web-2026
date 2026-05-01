<?php
?>

<div class="post-container <?= $index === 0 ? 'post-top' : 'post-bottom' ?>">
    <a href="http://localhost/post.php">
        <img src="/src/images/<?= htmlspecialchars($post['image']) ?>" alt="Post" class="post-image">
    </a>
</div>
<button class="like-button">
    <img src="/src/images/like.png" alt="Like" class="like-icon">
    <?= htmlspecialchars($post['likes']) ?>
</button>
<div class="post-text">
    <?= nl2br(htmlspecialchars($post['text'])) ?>
</div>
<div class="post-time"><?= htmlspecialchars($post['time']) ?></div>