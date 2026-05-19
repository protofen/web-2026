<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создание поста</title>
    <link rel="stylesheet" href="src/css/home.css">
</head>
<body>
    <div class="create-post-page">
        <div class="create-post-header">
            <a href="http://localhost/home.php" class="back-link">← Назад к ленте</a>
            <h1 class="create-post-title" id="pageTitle">Новый пост</h1>
        </div>

        <div class="create-post-content" id="createPostContent">
            <div class="images-section">
                <div class="images-area">
                    <div id="imagesGrid" style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; min-height: 200px;">
                        <div id="emptyImagesPlaceholder" style="width: 100%; text-align: center; padding: 50px; color: #999; background: #f4f4f4; border-radius: 10px;">
                            Нет добавленных фото
                        </div>
                    </div>
                    
                    <button class="add-photo-main-btn" id="addPhotoMainBtn">+ Добавить фото</button>
                    <button class="add-photo-text-btn" id="addPhotoTextBtn">Добавить фото</button>
                </div>
            </div>

            <div class="form-section">
                <textarea class="caption-input" id="postCaption" placeholder="Добавьте подпись..."></textarea>
                <button class="share-btn" id="shareBtn" disabled>Поделиться</button>
            </div>
        </div>

        <div id="successMessage" style="display: none; text-align: center; padding: 100px; background: #f4f4f4; border-radius: 20px;">
            <p style="font-size: 24px; color: #222; margin-bottom: 20px;" id="successText">✅ Пост успешно сохранен!</p>
            <button id="createNewPostBtn" style="padding: 12px 24px; background-color: #222; color: white; border: none; border-radius: 10px; cursor: pointer; font-family: Golos-UI_Regular; font-size: 16px;">Создать новый пост</button>
        </div>

        <div id="errorMessage" style="display: none; text-align: center; padding: 20px; background: #fff0f0; border-radius: 20px; margin-top: 20px; border: 1px solid #ff0000;">
            <p style="font-size: 16px; color: #ff0000;">❌ Ошибка при сохранении поста. Попробуйте снова.</p>
        </div>
    </div>

    <input type="file" id="fileInput" accept="image/*" multiple style="display: none;">

    <script>
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const postId = urlParams.get('id');
            const isEditing = postId !== null;
            
            const pageTitle = document.getElementById('pageTitle');
            const shareBtn = document.getElementById('shareBtn');
            const createPostContent = document.getElementById('createPostContent');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const createNewPostBtn = document.getElementById('createNewPostBtn');
            const successText = document.getElementById('successText');
            
            const fileInput = document.getElementById('fileInput');
            const addPhotoMainBtn = document.getElementById('addPhotoMainBtn');
            const addPhotoTextBtn = document.getElementById('addPhotoTextBtn');
            const imagesGrid = document.getElementById('imagesGrid');
            const emptyPlaceholder = document.getElementById('emptyImagesPlaceholder');
            const postCaption = document.getElementById('postCaption');
            
            let images = [];
            let existingImages = [];
            let currentPostId = null;
            
            const DEFAULT_MAX_WIDTH = 520;
            const DEFAULT_MAX_HEIGHT = 400;
            
            if (isEditing) {
                pageTitle.textContent = 'Редактирование поста';
                shareBtn.textContent = 'Сохранить';
                loadPostForEditing(postId);
            }
            
            async function loadPostForEditing(id) {
                try {
                    const response = await fetch(`api.php?action=get_post&id=${id}`);
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        const post = result.post;
                        currentPostId = post.id;
                        postCaption.value = post.text;
                        
                        if (post.images && post.images.length > 0) {
                            for (const imgName of post.images) {
                                const imgPath = `/src/images/${imgName}`;
                                const response = await fetch(imgPath);
                                const blob = await response.blob();
                                const base64 = await getBlobBase64(blob);
                                const url = URL.createObjectURL(blob);
                                
                                images.push({
                                    url: url,
                                    blob: blob,
                                    base64: base64,
                                    name: imgName,
                                    isExisting: true
                                });
                            }
                            renderImages();
                        }
                        
                        validateForm();
                    } else {
                        console.error('Пост не найден');
                        window.location.href = 'http://localhost/home.php';
                    }
                } catch (error) {
                    console.error('Ошибка загрузки поста:', error);
                    window.location.href = 'http://localhost/home.php';
                }
            }
            
            function getBlobBase64(blob) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(blob);
                });
            }
            
            function validateForm() {
                const hasImages = images.length > 0;
                const hasText = postCaption.value.trim().length > 0;
                shareBtn.disabled = !(hasImages && hasText);
            }
            
            function resizeImage(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            let width = img.width;
                            let height = img.height;
                            
                            if (width > DEFAULT_MAX_WIDTH || height > DEFAULT_MAX_HEIGHT) {
                                const widthRatio = DEFAULT_MAX_WIDTH / width;
                                const heightRatio = DEFAULT_MAX_HEIGHT / height;
                                const ratio = Math.min(widthRatio, heightRatio);
                                width = Math.floor(width * ratio);
                                height = Math.floor(height * ratio);
                            }
                            
                            const canvas = document.createElement('canvas');
                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);
                            
                            canvas.toBlob(function(blob) {
                                resolve(blob);
                            }, file.type);
                        };
                        img.onerror = reject;
                        img.src = e.target.result;
                    };
                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });
            }
            
            function getImageBase64(blob) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = function() {
                        resolve(reader.result);
                    };
                    reader.onerror = reject;
                    reader.readAsDataURL(blob);
                });
            }
            
            async function addImages(files) {
                const filesArray = Array.from(files);
                
                for (const file of filesArray) {
                    if (!file.type.startsWith('image/')) continue;
                    
                    try {
                        const resizedBlob = await resizeImage(file);
                        const url = URL.createObjectURL(resizedBlob);
                        const base64 = await getImageBase64(resizedBlob);
                        images.push({
                            url: url,
                            blob: resizedBlob,
                            base64: base64,
                            name: file.name,
                            isExisting: false
                        });
                        renderImages();
                        validateForm();
                    } catch (error) {
                        console.error('Ошибка при обработке изображения:', error);
                    }
                }
            }
            
            function renderImages() {
                if (images.length === 0) {
                    emptyPlaceholder.style.display = 'block';
                    return;
                }
                
                emptyPlaceholder.style.display = 'none';
                
                const imagesHtml = images.map((img, index) => {
                    return `
                        <div class="image-preview-item" style="position: relative; width: 150px; height: 150px; border-radius: 10px; overflow: hidden; background: #e0e0e0;">
                            <img src="${img.url}" style="width: 100%; height: 100%; object-fit: cover;">
                            <button class="remove-image-btn" data-index="${index}" style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; display: flex; align-items: center; justify-content: center;">✕</button>
                        </div>
                    `;
                }).join('');
                
                imagesGrid.innerHTML = imagesHtml;
                
                document.querySelectorAll('.remove-image-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const index = parseInt(btn.getAttribute('data-index'));
                        removeImage(index);
                    });
                });
            }
            
            function removeImage(index) {
                if (index >= 0 && index < images.length) {
                    URL.revokeObjectURL(images[index].url);
                    images.splice(index, 1);
                    renderImages();
                    validateForm();
                }
            }
            
            function resetForm() {
                images.forEach(img => URL.revokeObjectURL(img.url));
                images = [];
                postCaption.value = '';
                renderImages();
                validateForm();
                errorMessage.style.display = 'none';
            }
            
            async function savePost() {
                if (shareBtn.disabled) return;
                
                shareBtn.disabled = true;
                shareBtn.textContent = isEditing ? 'Сохранение...' : 'Публикация...';
                errorMessage.style.display = 'none';
                
                const postData = {
                    id: isEditing ? currentPostId : Date.now(),
                    title: 'Новый пост',
                    subtitle: '',
                    author: 'User',
                    author_avatar: 'pfp.png',
                    likes: 0,
                    text: postCaption.value.trim(),
                    time: 'только что',
                    image: images.length > 0 ? images[0].name : '',
                    images: images.map(img => img.name),
                    imagesBase64: images.filter(img => !img.isExisting).map(img => img.base64),
                    existingImages: images.filter(img => img.isExisting).map(img => img.name)
                };
                
                try {
                    const action = isEditing ? 'update_post' : 'create_post';
                    const response = await fetch('api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: action,
                            post: postData
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        createPostContent.style.display = 'none';
                        successText.textContent = isEditing ? '✅ Пост успешно обновлен!' : '✅ Пост успешно сохранен!';
                        successMessage.style.display = 'block';
                        resetForm();
                    } else {
                        throw new Error(result.error || 'Ошибка сохранения');
                    }
                } catch (error) {
                    console.error('Ошибка:', error);
                    errorMessage.style.display = 'block';
                    shareBtn.disabled = false;
                    shareBtn.textContent = isEditing ? 'Сохранить' : 'Поделиться';
                    
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                    }, 5000);
                }
            }
            
            function createNewPost() {
                window.location.href = 'http://localhost/create_post.php';
            }
            
            addPhotoMainBtn.addEventListener('click', () => {
                fileInput.click();
            });
            
            addPhotoTextBtn.addEventListener('click', () => {
                fileInput.click();
            });
            
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    addImages(e.target.files);
                    fileInput.value = '';
                }
            });
            
            postCaption.addEventListener('input', validateForm);
            shareBtn.addEventListener('click', savePost);
            createNewPostBtn.addEventListener('click', createNewPost);
            
            validateForm();
        })();
    </script>
</body>
</html>