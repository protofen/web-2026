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
                    
                } else {
                    textElement.style.maxHeight = 'none';
                    textElement.style.overflow = 'visible';
                    textElement.style.webkitLineClamp = 'unset';
                }
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
            }
            
        });
    }
    
    initExpandableText();
})();