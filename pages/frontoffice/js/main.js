/* =============================================
   VERTONEWS - Main JavaScript
   ============================================= */

// ---- Category filter tabs ----
function initCatFilters() {
  document.querySelectorAll('.cat-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('.cat-filters').querySelectorAll('.cat-btn')
        .forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });
}

// ---- Carousel dots ----
function initCarousel() {
  const dots = document.querySelectorAll('.carousel-dots .dot');
  const cards = document.querySelector('.breaking-carousel');
  if (!cards || !dots.length) return;

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      dots.forEach(d => d.classList.remove('active'));
      dot.classList.add('active');
    });
  });

  cards.addEventListener('scroll', () => {
    const idx = Math.round(cards.scrollLeft / cards.offsetWidth);
    dots.forEach((d, i) => d.classList.toggle('active', i === idx));
  });
}

// ---- Desktop category nav underline ----
function initDesktopNav() {
  document.querySelectorAll('.nav-categories a').forEach(a => {
    a.addEventListener('click', e => {
      e.preventDefault();
      document.querySelectorAll('.nav-categories a')
        .forEach(x => x.classList.remove('active'));
      a.classList.add('active');
    });
  });
}

// ---- Mobile bottom nav ----
function initMobileNav() {
  document.querySelectorAll('.mob-nav-item').forEach(item => {
    item.addEventListener('click', () => {
      document.querySelectorAll('.mob-nav-item').forEach(x => {
        x.classList.remove('active');
        if (x.classList.contains('active-home')) {
          x.classList.remove('active-home');
        }
      });
    });
  });
}

// ---- Page navigation (SPA-like) ----
function navigate(href) {
  window.location.href = href;
}

// ---- Search focus ----
function initSearch() {
  const bars = document.querySelectorAll('.search-bar');
  bars.forEach(bar => {
    const input = bar.querySelector('input');
    if (!input) return;
    bar.addEventListener('click', () => input.focus());
  });
}

// ---- Smooth scroll to top ----
function scrollTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ---- Back button ----
function goBack() {
  if (history.length > 1) history.back();
  else window.location.href = '../index.html';
}

// ---- Init all ----
document.addEventListener('DOMContentLoaded', () => {
  initCatFilters();
  initCarousel();
  initDesktopNav();
  initMobileNav();
  initSearch();
});
