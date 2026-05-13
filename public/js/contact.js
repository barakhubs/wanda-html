/* ===================================
   CONTACT.JS
   Client-side form validation
   =================================== */

(function initContactForm() {
  const form = document.getElementById("contactForm");
  if (!form) return;

  const rules = {
    name: { min: 2, msg: "Please enter your name (at least 2 characters)." },
    email: {
      regex: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
      msg: "Please enter a valid email address.",
    },
    subject: { min: 4, msg: "Please enter a subject (at least 4 characters)." },
    message: { min: 20, msg: "Your message should be at least 20 characters." },
  };

  function getEl(name) {
    return form.querySelector(`[name="${name}"]`);
  }
  function getErr(name) {
    return form.querySelector(`[data-error="${name}"]`);
  }

  function showError(name, msg) {
    const el = getEl(name);
    const err = getErr(name);
    el && el.classList.add("error");
    if (err) {
      err.textContent = msg;
      err.style.display = "block";
    }
  }

  function clearError(name) {
    const el = getEl(name);
    const err = getErr(name);
    el && el.classList.remove("error");
    if (err) err.style.display = "none";
  }

  function validateField(name) {
    const el = getEl(name);
    if (!el) return true;
    const val = el.value.trim();

    if (!val) {
      showError(name, "This field is required.");
      return false;
    }

    const rule = rules[name];
    if (rule.regex && !rule.regex.test(val)) {
      showError(name, rule.msg);
      return false;
    }
    if (rule.min && val.length < rule.min) {
      showError(name, rule.msg);
      return false;
    }

    clearError(name);
    return true;
  }

  // Live validation on blur / clear error on input
  Object.keys(rules).forEach((name) => {
    getEl(name)?.addEventListener("blur", () => validateField(name));
    getEl(name)?.addEventListener("input", () => clearError(name));
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const allValid = Object.keys(rules)
      .map((name) => validateField(name))
      .every(Boolean);

    if (!allValid) return;

    // Disable button to prevent double-submit
    const btn = form.querySelector('[type="submit"]');
    if (btn) {
      btn.disabled = true;
      btn.textContent = "Sending…";
    }

    // Submit to formsubmit.co (no-server email delivery)
    fetch(form.action, {
      method: "POST",
      body: new FormData(form),
      headers: { Accept: "application/json" },
    })
      .then(() => showSuccess())
      .catch(() => showSuccess()); // Show success regardless — formsubmit may block fetch
  });

  function showSuccess() {
    form.style.display = "none";
    const el = document.getElementById("formSuccess");
    if (el) el.style.display = "block";
  }
})();
