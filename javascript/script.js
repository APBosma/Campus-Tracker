let slideIndex = 0;
let slideTimer;

showSlides();

/**
 * Displays the slides and allows for the user to move to the next
 */
function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
    for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  autoSlides();
} 

/**
 * Moves to the next slide ever so many seconds
 */
function autoSlides() {
  slideTimer = setTimeout(showSlides, 10000); 
}


showSlides2(slideIndex);

/**
 * Moves to the next slide and resets the timer
 * 
 * @param {int} n - Index of the current slide
 */
function nextSlide(n) {
  clearTimeout(slideTimer);
  showSlides2(slideIndex += n);
  autoSlides();
}

/**
 * Runs the current slide information
 * 
 * @param {int} n - Index of the current slide
 */
function currentSlide(n) {
  clearTimeout(slideTimer);
  showSlides2(slideIndex = n);
  autoSlides();
}

/**
 * Sets up the slides and their information
 * 
 * @param {int} n - Index of the current slide
 */
function showSlides2(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
} 