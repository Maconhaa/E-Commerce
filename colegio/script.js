let currentIndex = 0;

function moveSlide(direction) {
    const slides = document.querySelectorAll('.image-slide');
    const totalSlides = slides.length;

    currentIndex = (currentIndex + direction + totalSlides) % totalSlides; // Calcula el índice circular

    const newTransformValue = -100 * currentIndex + '%';
    document.querySelector('.image-container').style.transform = `translateX(${newTransformValue})`;
}
