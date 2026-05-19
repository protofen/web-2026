(function() {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalClose = document.getElementById('modalClose');
    
    let isModalOpen = false;
    let escHandler = null;
    
    function openModal(imageSrc) {
        modalImage.src = imageSrc;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        isModalOpen = true;
        
        if (escHandler) {
            document.removeEventListener('keydown', escHandler);
        }
        
        escHandler = function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        };
        
        document.addEventListener('keydown', escHandler);
    }
    
    function closeModal() {
        modal.classList.remove('active');
        modalImage.src = '';
        document.body.style.overflow = '';
        isModalOpen = false;
        
        if (escHandler) {
            document.removeEventListener('keydown', escHandler);
            escHandler = null;
        }
    }
    
    modalClose.addEventListener('click', closeModal);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            e.stopPropagation();
        }
    });
    
    document.querySelectorAll('.post-slider .modal-trigger').forEach(img => {
        img.addEventListener('click', function(e) {
            e.stopPropagation();
            const imageSrc = this.src;
            openModal(imageSrc);
        });
    });
    
    const firstSlider = document.querySelector('.post-slider');
    if (firstSlider) {
        const track = firstSlider.querySelector('[data-track]');
        const prevBtn = firstSlider.querySelector('[data-prev]');
        const nextBtn = firstSlider.querySelector('[data-next]');
        const indicator = firstSlider.querySelector('[data-indicator]');
        
        if (track) {
            const slides = track.querySelectorAll('.slider-slide');
            const totalSlides = slides.length;
            
            if (totalSlides > 1) {
                let currentIndex = 0;
                
                function updateSlider() {
                    const offset = -currentIndex * 100;
                    track.style.transform = `translateX(${offset}%)`;
                    if (indicator) {
                        const currentSpan = indicator.querySelector('.current');
                        if (currentSpan) {
                            currentSpan.textContent = currentIndex + 1;
                        }
                    }
                }
                
                function nextSlide() {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    updateSlider();
                }
                
                function prevSlide() {
                    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                    updateSlider();
                }
                
                prevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    prevSlide();
                });
                
                nextBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    nextSlide();
                });
            }
        }
    }
    
    function initExpandableText() {
        const postTextElements = document.querySelectorAll('.post-text');
        
        postTextElements.forEach((container) => {
            const originalText = container.getAttribute('data-full-text');
            if (!originalText) return;
            
            const textElement = document.createElement('div');
            textElement.className = 'post-text-content';
            textElement.textContent = originalText;
            
            container.innerHTML = '';
            container.appendChild(textElement);
            
            const expandBtn = document.createElement('button');
            expandBtn.className = 'expand-btn';
            expandBtn.textContent = 'ещё';
            
            function checkOverflow() {
                const lineHeight = parseFloat(getComputedStyle(textElement).lineHeight);
                const maxHeight = lineHeight * 2;
                const isOverflow = textElement.scrollHeight > maxHeight + 2;
                
                if (isOverflow) {
                    textElement.style.maxHeight = maxHeight + 'px';
                    textElement.style.overflow = 'hidden';
                    textElement.style.display = '-webkit-box';
                    textElement.style.webkitLineClamp = '2';
                    textElement.style.webkitBoxOrient = 'vertical';
                    textElement.style.display = 'block';
                    
                    if (!container.querySelector('.expand-btn')) {
                        container.appendChild(expandBtn);
                    }
                    
                    expandBtn.style.display = 'inline-block';
                } else {
                    textElement.style.maxHeight = 'none';
                    textElement.style.overflow = 'visible';
                    textElement.style.webkitLineClamp = 'unset';
                    
                    if (expandBtn.parentNode) {
                        expandBtn.style.display = 'none';
                    }
                }
            }
            
            function expandText() {
                textElement.style.maxHeight = 'none';
                textElement.style.overflow = 'visible';
                textElement.style.webkitLineClamp = 'unset';
                expandBtn.textContent = 'свернуть';
            }
            
            function collapseText() {
                const lineHeight = parseFloat(getComputedStyle(textElement).lineHeight);
                const maxHeight = lineHeight * 2;
                textElement.style.maxHeight = maxHeight + 'px';
                textElement.style.overflow = 'hidden';
                textElement.style.display = '-webkit-box';
                textElement.style.webkitLineClamp = '2';
                textElement.style.webkitBoxOrient = 'vertical';
                textElement.style.display = 'block';
                expandBtn.textContent = 'ещё';
            }
            
            function toggleText() {
                if (expandBtn.textContent === 'ещё') {
                    expandText();
                } else {
                    collapseText();
                }
            }
            
            expandBtn.addEventListener('click', toggleText);
            
            checkOverflow();
            window.addEventListener('resize', checkOverflow);
        });
    }
    
    function initLikes() {
        const likeButtons = document.querySelectorAll('.like-button');
        
        likeButtons.forEach((button) => {
            const postContainer = button.closest('.post-container');
            if (!postContainer) return;
            
            let postId = button.getAttribute('data-post-id');
            if (!postId) {
                const slider = postContainer.querySelector('.post-slider');
                const img = postContainer.querySelector('.post-image');
                if (slider && slider.getAttribute('data-post-id')) {
                    postId = slider.getAttribute('data-post-id');
                } else if (img && img.getAttribute('data-post-id')) {
                    postId = img.getAttribute('data-post-id');
                } else {
                    const profileCard = document.querySelector('.profile-card');
                    if (profileCard && profileCard.classList.contains('profile-top')) {
                        postId = '1';
                    } else {
                        postId = '2';
                    }
                }
                button.setAttribute('data-post-id', postId);
            }
            
            button.removeEventListener('click', handleLikeClick);
            button.addEventListener('click', handleLikeClick);
        });
    }
    
    async function handleLikeClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const button = e.currentTarget;
        const postId = button.getAttribute('data-post-id');
        const likesSpan = button.querySelector('.likes-count');
        
        if (!likesSpan) return;
        
        const currentLikes = parseInt(likesSpan.textContent);
        
        button.disabled = true;
        
        const errorDiv = document.getElementById('likeError');
        if (errorDiv) errorDiv.remove();
        
        try {
            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'toggle_like',
                    post_id: parseInt(postId)
                })
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                likesSpan.textContent = result.likes;
            } else {
                throw new Error(result.error || 'Ошибка при изменении лайка');
            }
        } catch (error) {
            console.error('Ошибка:', error);
            
            const errorMessage = document.createElement('div');
            errorMessage.id = 'likeError';
            errorMessage.textContent = '❌ Не удалось поставить лайк. Попробуйте снова.';
            errorMessage.style.cssText = 'position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: #ff4444; color: white; padding: 12px 24px; border-radius: 10px; z-index: 2000; font-family: Golos-UI_Regular; font-size: 14px;';
            
            document.body.appendChild(errorMessage);
            
            setTimeout(() => {
                errorMessage.remove();
            }, 3000);
        } finally {
            button.disabled = false;
        }
    }
    
    initExpandableText();
    initLikes();
    
    const observer = new MutationObserver(function() {
        initLikes();
    });
    
    observer.observe(document.body, { childList: true, subtree: true });
})();