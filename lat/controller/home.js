// Fungsi scroll ke atas
const goToTop = () => {
  location.href = "#header";
};

// Animasi scroll
const animateOnScroll = () => {
  const elements = document.querySelectorAll('.profil, .program');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1
  });

  elements.forEach(element => {
    observer.observe(element);
  });
};

document.addEventListener('DOMContentLoaded', animateOnScroll);

// Kontrol musik
const musicToggle = document.getElementById('musicToggle');
const backgroundMusic = document.getElementById('backgroundMusic');
let isPlaying = false;

musicToggle.addEventListener('click', () => {
  if (isPlaying) {
    backgroundMusic.pause();
    musicToggle.classList.remove('playing');
  } else {
    backgroundMusic.play();
    musicToggle.classList.add('playing');
  }
  isPlaying = !isPlaying;
});

// Smooth scroll
function smoothScroll(target, duration) {
  var target = document.querySelector(target);
  var targetPosition = target.getBoundingClientRect().top;
  var startPosition = window.pageYOffset;
  var distance = targetPosition - startPosition;
  var startTime = null;

  function animation(currentTime) {
    if (startTime === null) startTime = currentTime;
    var timeElapsed = currentTime - startTime;
    var run = ease(timeElapsed, startPosition, distance, duration);
    window.scrollTo(0, run);
    if (timeElapsed < duration) requestAnimationFrame(animation);
  }

  function ease(t, b, c, d) {
    t /= d / 2;
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c / 2 * (t * (t - 2) - 1) + b;
  }

  requestAnimationFrame(animation);
}

var topButton = document.getElementById('topButton');
topButton.addEventListener('click', function(e) {
  e.preventDefault();
  smoothScroll('body', 1000);
});

window.addEventListener('scroll', function() {
  if (window.pageYOffset > 300) {
    topButton.style.display = 'flex';
  } else {
    topButton.style.display = 'none';
  }
});

// Smooth scroll link internal
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    
    document.body.classList.add('scrolling');
    
    setTimeout(() => {
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    }, 10);

    setTimeout(() => {
      document.body.classList.remove('scrolling');
    }, 1000);
  });
});