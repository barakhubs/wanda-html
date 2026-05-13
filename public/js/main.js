/* ===================================
   MAIN.JS
   Navigation · Scroll Reveal · Testimonials
   =================================== */

// ── Navbar: transparent → white on scroll ───────────────────────
const navbar = document.getElementById("navbar");

function updateNavbar() {
  if (navbar) navbar.classList.toggle("scrolled", window.scrollY > 60);
}
window.addEventListener("scroll", updateNavbar, { passive: true });
updateNavbar();

// ── Mobile Hamburger ─────────────────────────────────────────────
const hamburger = document.getElementById("hamburger");
const navLinks = document.getElementById("navLinks");
const navOverlay = document.getElementById("navOverlay");

function openMenu() {
  navLinks.classList.add("open");
  hamburger.classList.add("active");
  hamburger.setAttribute("aria-expanded", "true");
  navOverlay && navOverlay.classList.add("active");
  document.body.style.overflow = "hidden";
}

function closeMenu() {
  navLinks.classList.remove("open");
  hamburger.classList.remove("active");
  hamburger.setAttribute("aria-expanded", "false");
  navOverlay && navOverlay.classList.remove("active");
  document.body.style.overflow = "";
}

if (hamburger) {
  hamburger.addEventListener("click", () =>
    navLinks.classList.contains("open") ? closeMenu() : openMenu(),
  );
}
navOverlay && navOverlay.addEventListener("click", closeMenu);
document
  .querySelectorAll(".nav-links a")
  .forEach((link) => link.addEventListener("click", closeMenu));
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closeMenu();
});

// ── Active nav link ──────────────────────────────────────────────
(function setActiveLink() {
  const file = window.location.pathname.split("/").pop() || "index.html";
  document.querySelectorAll(".nav-links a[href]").forEach((link) => {
    if (link.getAttribute("href") === file) link.classList.add("active");
  });
})();

// ── Scroll Reveal ────────────────────────────────────────────────
const revealObserver = new IntersectionObserver(
  (entries) =>
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        revealObserver.unobserve(entry.target);
      }
    }),
  { threshold: 0.1, rootMargin: "0px 0px -40px 0px" },
);

document
  .querySelectorAll(".reveal, .reveal-left, .reveal-right")
  .forEach((el) => revealObserver.observe(el));

// ── Testimonials Slider ──────────────────────────────────────────
(function initTestimonials() {
  const slider = document.querySelector(".testimonials-slider");
  if (!slider) return;

  const slides = slider.querySelectorAll(".testimonial-slide");
  const dots = document.querySelectorAll(".dot");
  let current = 0;
  let autoplay;

  function goTo(index) {
    current = ((index % slides.length) + slides.length) % slides.length;
    slider.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle("active", i === current));
  }

  document.querySelector(".slider-prev")?.addEventListener("click", () => {
    goTo(current - 1);
    reset();
  });
  document.querySelector(".slider-next")?.addEventListener("click", () => {
    goTo(current + 1);
    reset();
  });
  dots.forEach((dot, i) =>
    dot.addEventListener("click", () => {
      goTo(i);
      reset();
    }),
  );

  function start() {
    autoplay = setInterval(() => goTo(current + 1), 5000);
  }
  function reset() {
    clearInterval(autoplay);
    start();
  }

  goTo(0);
  start();
})();

// ── Smooth-scroll for anchor links ──────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", (e) => {
    const target = document.querySelector(anchor.getAttribute("href"));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  });
});

// ── Count-Up Animation ───────────────────────────────────────────
(function initCountUp() {
  const counters = document.querySelectorAll("[data-count]");
  if (!counters.length) return;

  function animateCount(el) {
    const target = +el.dataset.count;
    const suffix = el.dataset.suffix || "";
    const duration = 2000;
    const step = 16;
    const increment = target / (duration / step);
    let current = 0;

    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      el.textContent = Math.floor(current).toLocaleString() + suffix;
    }, step);
  }

  const countObserver = new IntersectionObserver(
    (entries) =>
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCount(entry.target);
          countObserver.unobserve(entry.target);
        }
      }),
    { threshold: 0.3 },
  );

  counters.forEach((el) => countObserver.observe(el));
})();
