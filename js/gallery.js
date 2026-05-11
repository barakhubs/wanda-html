/* ===================================
   GALLERY.JS
   Portfolio filter + Lightbox
   =================================== */

// ── Portfolio Filter ─────────────────────────────────────────────
(function initFilter() {
  const filterBtns = document.querySelectorAll(".filter-btn");
  const items = document.querySelectorAll(".portfolio-item");
  if (!filterBtns.length) return;

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      const filter = btn.dataset.filter;
      items.forEach((item) => {
        const match = filter === "all" || item.dataset.category === filter;
        item.classList.toggle("hidden", !match);
      });
    });
  });
})();

// ── Blog Filter ──────────────────────────────────────────────────
(function initBlogFilter() {
  const filterBtns = document.querySelectorAll(".blog-filter .filter-btn");
  const cards = document.querySelectorAll(".blog-card");
  if (!filterBtns.length) return;

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      const filter = btn.dataset.filter;
      cards.forEach((card) => {
        const match = filter === "all" || card.dataset.category === filter;
        card.classList.toggle("hidden", !match);
      });
    });
  });
})();

// ── Lightbox ─────────────────────────────────────────────────────
(function initLightbox() {
  const lightbox = document.getElementById("lightbox");
  const lbClose = document.getElementById("lightboxClose");
  const lbTitle = document.getElementById("lbTitle");
  const lbDesc = document.getElementById("lbDesc");
  const lbCategory = document.getElementById("lbCategory");
  if (!lightbox) return;

  document.querySelectorAll(".portfolio-item").forEach((item) => {
    item.addEventListener("click", () => {
      lbTitle.textContent = item.dataset.title || "";
      lbDesc.textContent = item.dataset.desc || "";
      lbCategory.textContent = item.dataset.category || "";
      openLightbox();
    });
  });

  function openLightbox() {
    lightbox.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closeLightbox() {
    lightbox.classList.remove("active");
    document.body.style.overflow = "";
  }

  lbClose?.addEventListener("click", closeLightbox);
  lightbox.addEventListener("click", (e) => {
    if (e.target === lightbox) closeLightbox();
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeLightbox();
  });
})();
