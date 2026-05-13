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
            <h1 class="create-post-title">Новый пост</h1>
        </div>

        <div class="create-post-content">
            <div class="images-section">
                <div class="images-area">
                    <div id="imagesGrid" style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 20px; min-height: 200px;">
                        <div id="emptyImagesPlaceholder" style="width: 100%; text-align: center; padding: 50px; color: #999; background: #f4f4f4; border-radius: 10px;">
                            Нет добавленных фото
                        </div>
                    </div>
                    
                    <button class="add-photo-main-btn" id="addPhotoMainBtn">+ Добавить фото</button>
                </div>
            </div>

            <div class="form-section">
                <textarea class="caption-input" id="postCaption" placeholder="Добавьте подпись..."></textarea>
                <button class="share-btn" id="shareBtn" disabled>Поделиться</button>
            </div>
        </div>
    </div>

    <input type="file" id="fileInput" accept="image/*" multiple style="display: none;">

    <script>
        (function() {
            const fileInput = document.getElementById('fileInput');
            const addPhotoMainBtn = document.getElementById('addPhotoMainBtn');
            const imagesGrid = document.getElementById('imagesGrid');
            const emptyPlaceholder = document.getElementById('emptyImagesPlaceholder');
            const postCaption = document.getElementById('postCaption');
            const shareBtn = document.getElementById('shareBtn');
            
            let images = [];
            
            const DEFAULT_MAX_WIDTH = 520;
            const DEFAULT_MAX_HEIGHT = 400;
            
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
            
            function addImages(files) {
                const filesArray = Array.from(files);
                
                filesArray.forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    
                    resizeImage(file).then(resizedBlob => {
                        const url = URL.createObjectURL(resizedBlob);
                        images.push({
                            url: url,
                            blob: resizedBlob,
                            name: file.name
                        });
                        renderImages();
                        validateForm();
                    });
                });
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
            
            addPhotoMainBtn.addEventListener('click', () => {
                fileInput.click();
            });
            
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    addImages(e.target.files);
                    fileInput.value = '';
                }
            });
            
            postCaption.addEventListener('input', validateForm);
            
            shareBtn.addEventListener('click', () => {
                if (shareBtn.disabled) return;
                
                const postData = {
                    id: Date.now(),
                    title: 'Новый пост',
                    subtitle: '',
                    author: 'User',
                    author_avatar: 'pfp.png',
                    likes: 0,
                    text: postCaption.value.trim(),
                    time: 'только что',
                    image: images.length > 0 ? images[0].name : '',
                    images: images.map(img => img.name)
                };
                
                console.log('Новый пост:', postData);
                console.log(JSON.stringify(postData, null, 2));
                
                alert('Пост создан');
                postCaption.value = '';
                images.forEach(img => URL.revokeObjectURL(img.url));
                images = [];
                renderImages();
                validateForm();
            });
            
            validateForm();
        })();
    </script>
</body>
</html>