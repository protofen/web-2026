<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST' && !($method === 'GET' && isset($_GET['action']))) {
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Only POST requests allowed']);
        exit;
    }
}

$postsFile = __DIR__ . '/posts.json';
$likesFile = __DIR__ . '/likes.json';

if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_post') {
    $postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!file_exists($postsFile)) {
        http_response_code(404);
        echo json_encode(['error' => 'No posts found']);
        exit;
    }
    
    $posts = json_decode(file_get_contents($postsFile), true);
    if (!is_array($posts)) {
        $posts = [];
    }
    
    $foundPost = null;
    foreach ($posts as $post) {
        if ($post['id'] == $postId) {
            $foundPost = $post;
            break;
        }
    }
    
    if ($foundPost) {
        echo json_encode(['success' => true, 'post' => $foundPost]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Post not found']);
    }
    exit;
}

$inputJSON = file_get_contents('php://input');
$requestData = json_decode($inputJSON, true);

if (isset($requestData['action']) && $requestData['action'] === 'toggle_like') {
    $postId = isset($requestData['post_id']) ? (int)$requestData['post_id'] : 0;
    $userId = 'current_user';
    
    if (!$postId) {
        http_response_code(400);
        echo json_encode(['error' => 'Post ID is required']);
        exit;
    }
    
    if (!file_exists($postsFile)) {
        http_response_code(404);
        echo json_encode(['error' => 'No posts found']);
        exit;
    }
    
    $posts = json_decode(file_get_contents($postsFile), true);
    if (!is_array($posts)) {
        $posts = [];
    }
    
    $likes = [];
    if (file_exists($likesFile)) {
        $likes = json_decode(file_get_contents($likesFile), true);
        if (!is_array($likes)) {
            $likes = [];
        }
    }
    
    if (!isset($likes[$postId])) {
        $likes[$postId] = [];
    }
    
    if (in_array($userId, $likes[$postId])) {
        $likes[$postId] = array_filter($likes[$postId], function($uid) use ($userId) {
            return $uid !== $userId;
        });
        $likes[$postId] = array_values($likes[$postId]);
        
        if (empty($likes[$postId])) {
            unset($likes[$postId]);
        }
    } else {
        $likes[$postId][] = $userId;
    }
    
    file_put_contents($likesFile, json_encode($likes, JSON_PRETTY_PRINT));
    
    $newLikesCount = isset($likes[$postId]) ? count($likes[$postId]) : 0;
    
    foreach ($posts as &$post) {
        if ($post['id'] == $postId) {
            $post['likes'] = $newLikesCount;
            break;
        }
    }
    
    file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo json_encode(['success' => true, 'likes' => $newLikesCount]);
    exit;
}

if (isset($requestData['action']) && $requestData['action'] === 'create_post') {
    $post = $requestData['post'];
    
    if ((!isset($post['imagesBase64']) || empty($post['imagesBase64'])) && (!isset($post['existingImages']) || empty($post['existingImages']))) {
        http_response_code(400);
        echo json_encode(['error' => 'No images provided']);
        exit;
    }
    
    if (!isset($post['text']) || empty(trim($post['text']))) {
        http_response_code(400);
        echo json_encode(['error' => 'Post text is required']);
        exit;
    }
    
    $uploadDir = __DIR__ . '/src/images';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $savedImages = [];
    
    if (isset($post['existingImages']) && is_array($post['existingImages'])) {
        $savedImages = $post['existingImages'];
    }
    
    if (isset($post['imagesBase64']) && is_array($post['imagesBase64'])) {
        foreach ($post['imagesBase64'] as $index => $base64Image) {
            if (strpos($base64Image, 'base64,') !== false) {
                $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            }
            
            $decodedImage = base64_decode($base64Image);
            
            $extension = 'png';
            $filename = time() . '_' . ($index + count($savedImages)) . '.' . $extension;
            $filepath = $uploadDir . '/' . $filename;
            
            file_put_contents($filepath, $decodedImage);
            $savedImages[] = $filename;
        }
    }
    
    $existingPosts = [];
    if (file_exists($postsFile)) {
        $existingPosts = json_decode(file_get_contents($postsFile), true);
        if (!is_array($existingPosts)) {
            $existingPosts = [];
        }
    }
    
    $newPost = [
        'id' => $post['id'],
        'title' => $post['title'],
        'subtitle' => $post['subtitle'],
        'author' => $post['author'],
        'author_avatar' => $post['author_avatar'],
        'likes' => 0,
        'text' => $post['text'],
        'time' => $post['time'],
        'image' => $savedImages[0] ?? '',
        'images' => $savedImages
    ];
    
    array_unshift($existingPosts, $newPost);
    file_put_contents($postsFile, json_encode($existingPosts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo json_encode(['success' => true, 'post' => $newPost]);
    exit;
}

if (isset($requestData['action']) && $requestData['action'] === 'update_post') {
    $post = $requestData['post'];
    
    if ((!isset($post['imagesBase64']) || empty($post['imagesBase64'])) && (!isset($post['existingImages']) || empty($post['existingImages']))) {
        http_response_code(400);
        echo json_encode(['error' => 'No images provided']);
        exit;
    }
    
    if (!isset($post['text']) || empty(trim($post['text']))) {
        http_response_code(400);
        echo json_encode(['error' => 'Post text is required']);
        exit;
    }
    
    if (!file_exists($postsFile)) {
        http_response_code(404);
        echo json_encode(['error' => 'No posts found']);
        exit;
    }
    
    $existingPosts = json_decode(file_get_contents($postsFile), true);
    if (!is_array($existingPosts)) {
        $existingPosts = [];
    }
    
    $uploadDir = __DIR__ . '/src/images';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $savedImages = [];
    
    if (isset($post['existingImages']) && is_array($post['existingImages'])) {
        $savedImages = $post['existingImages'];
    }
    
    if (isset($post['imagesBase64']) && is_array($post['imagesBase64'])) {
        foreach ($post['imagesBase64'] as $index => $base64Image) {
            if (strpos($base64Image, 'base64,') !== false) {
                $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            }
            
            $decodedImage = base64_decode($base64Image);
            
            $extension = 'png';
            $filename = time() . '_' . ($index + count($savedImages)) . '.' . $extension;
            $filepath = $uploadDir . '/' . $filename;
            
            file_put_contents($filepath, $decodedImage);
            $savedImages[] = $filename;
        }
    }
    
    $likes = [];
    if (file_exists($likesFile)) {
        $likes = json_decode(file_get_contents($likesFile), true);
        if (!is_array($likes)) {
            $likes = [];
        }
    }
    
    $updatedPost = [
        'id' => $post['id'],
        'title' => $post['title'],
        'subtitle' => $post['subtitle'],
        'author' => $post['author'],
        'author_avatar' => $post['author_avatar'],
        'likes' => isset($likes[$post['id']]) ? count($likes[$post['id']]) : 0,
        'text' => $post['text'],
        'time' => $post['time'],
        'image' => $savedImages[0] ?? '',
        'images' => $savedImages
    ];
    
    $postFound = false;
    foreach ($existingPosts as $key => $existingPost) {
        if ($existingPost['id'] == $post['id']) {
            $existingPosts[$key] = $updatedPost;
            $postFound = true;
            break;
        }
    }
    
    if (!$postFound) {
        array_unshift($existingPosts, $updatedPost);
    }
    
    file_put_contents($postsFile, json_encode($existingPosts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo json_encode(['success' => true, 'post' => $updatedPost]);
    exit;
}

if (!isset($requestData['image'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing image field']);
    exit;
}

$imageData = $requestData['image'];

if (strpos($imageData, 'base64,') !== false) {
    $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
}

$decodedImage = base64_decode($imageData);

$uploadDir = __DIR__ . '/static';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename = time() . '.png';
$filepath = $uploadDir . '/' . $filename;

file_put_contents($filepath, $decodedImage);

echo json_encode(['success' => true, 'file' => $filename]);