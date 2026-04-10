const menuButton = document.querySelector(".menu-toggle");
const mainNav = document.querySelector(".main-nav");

if (menuButton && mainNav) {
  menuButton.addEventListener("click", () => {
    const isOpen = mainNav.classList.toggle("open");
    menuButton.setAttribute("aria-expanded", String(isOpen));
  });

  mainNav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      mainNav.classList.remove("open");
      menuButton.setAttribute("aria-expanded", "false");
    });
  });
}

const revealNodes = document.querySelectorAll("[data-reveal]");

if (revealNodes.length > 0) {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.18 }
  );

  revealNodes.forEach((node, index) => {
    node.style.setProperty("--delay", `${Math.min(index * 70, 320)}ms`);
    observer.observe(node);
  });
}

const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
const logoTrack = document.querySelector("[data-logo-track]");

if (logoTrack && reduceMotion.matches) {
  logoTrack.style.animation = "none";
}

/* ── Cross-page hash scroll ──────────────────────────────────────────────── */
(function () {
  var hash = window.location.hash;
  if (!hash) return;

  function scrollToHash() {
    var target = document.querySelector(hash);
    if (!target) return;

    // Force-reveal si data-reveal cache la section
    if (target.hasAttribute("data-reveal")) {
      target.classList.add("is-visible");
    }
    var inner = target.querySelector("[data-reveal]");
    if (inner) inner.classList.add("is-visible");
    var parent = target.closest("[data-reveal]");
    if (parent) parent.classList.add("is-visible");

    target.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  if (document.readyState === "complete") {
    setTimeout(scrollToHash, 100);
  } else {
    window.addEventListener("load", function () {
      setTimeout(scrollToHash, 100);
    });
  }
})();
