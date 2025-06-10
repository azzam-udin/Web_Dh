// Fungsi scroll ke atas
const goToTop = () => {
  location.href = "#header";
};

// Animasi scroll
const animateOnScroll = () => {
  const elements = document.querySelectorAll('.profil, .program, .informasi, .topik');

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
topButton.addEventListener('click', function (e) {
  e.preventDefault();
  smoothScroll('body', 1000);
});

window.addEventListener('scroll', function () {
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

// Fungsi slideshow di bagian Galeri Foto dan Video
let slideIndex = 1;
showSlides(slideIndex, 'topik');

// mengatur slideshow otomatis
function autoShowSlides() {
  slideIndex++;
  showSlides(slideIndex, 'topik');
  setTimeout(autoShowSlides, 3000); // Ganti slide setiap 5 detik
}

autoShowSlides(); // Memanggil fungsi memulai slideshow otomatis

function plusSlides(n) {
  showSlides(slideIndex += n, 'topik');
}

function currentSlide(n) {
  showSlides(slideIndex = n, 'topik');
}

function showSlides(n, className) {
  let i;
  let slides = document.querySelector('.' + className).getElementsByClassName("mySlides");
  let dots = document.querySelector('.' + className).getElementsByClassName("dot");
  if (n > slides.length) { slideIndex = 1 }
  if (n < 1) { slideIndex = slides.length }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
}

const jadwalKelas = [
  { kelas: "Tahsin", hari: "Senin", jam: "05.00 - 06.00", infaq: "Gratis", waktu: "pagi" },
  { kelas: "Aqidah", hari: "Selasa", jam: "05.00 - 06.00", infaq: "Gratis", waktu: "pagi" },
  { kelas: "Mentoring", hari: "Rabu", jam: "05.00 - 06.00", infaq: "Gratis", waktu: "pagi" },
  { kelas: "Fiqh", hari: "Kamis", jam: "05.00 - 06.00", infaq: "Gratis", waktu: "pagi" },
  { kelas: "Adab", hari: "Jumat", jam: "05.00 - 06.00", infaq: "Gratis", waktu: "pagi" },
  { kelas: "Akhlak", hari: "Sabtu", jam: "05.00 - 06.00", infaq: "Gratis", waktu: "pagi" },
  { kelas: "Pengajian Umum", hari: "Ahad", jam: "05.00 - 06.00", infaq: "Gratis", waktu: "pagi" },
  { kelas: "Halaqah III", hari: "Senin", jam: "19.30 - 20.30", infaq: "Gratis", waktu: "malam" },
  { kelas: "Halaqah I", hari: "Selasa", jam: "19.30 - 20.30", infaq: "Gratis", waktu: "malam" },
  { kelas: "Halaqah II", hari: "Rabu", jam: "19.30 - 20.30", infaq: "Gratis", waktu: "malam" },
  { kelas: "Ushul Fiqh", hari: "Kamis", jam: "19.30 - 20.30", infaq: "Gratis", waktu: "malam" },
  { kelas: "B Arab", hari: "Jumat", jam: "19.30 - 20.30", infaq: "Gratis", waktu: "malam" },
  { kelas: "Musyawarah umum", hari: "Sabtu", jam: "19.30 - 20.30", infaq: "Gratis", waktu: "malam" },
  { kelas: "Halaqah IIII", hari: "Ahad", jam: "19.30 - 20.30", infaq: "Gratis", waktu: "malam" },
  { kelas: "STAIQ", hari: "Sesuai Kesepakatan", jam: "Sesuai Kesepakatan", infaq: "Gratis", waktu: "staiq" },
  { kelas: "STAIQ", hari: "Sesuai Kesepakatan", jam: "Sesuai Kesepakatan", infaq: "Gratis", waktu: "staiq" }
];


function tampilkanJadwal(filter = 'semua') {
  const jadwalBody = document.getElementById('jadwalBody');
  jadwalBody.innerHTML = '';

  jadwalKelas.forEach(jadwal => {
    if (
      filter === 'semua' ||
      (filter === 'pagi' && jadwal.waktu === 'pagi') ||
      (filter === 'malam' && jadwal.waktu === 'malam') ||
      (filter === 'staiq' && jadwal.waktu === 'staiq')
    ) {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${jadwal.kelas}</td>
        <td>${jadwal.hari}</td>
        <td>${jadwal.jam}</td>
        <td style="color: red;">${jadwal.infaq}</td>
      `;
      jadwalBody.appendChild(row);
    }
  });
}

// Event listener perubahan pada select
document.getElementById('kelasFilter').addEventListener('change', function () {
  tampilkanJadwal(this.value);
});

// Tampilkan semua jadwal saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
  tampilkanJadwal();
});