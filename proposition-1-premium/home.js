const mobileToggle = document.querySelector(".mobile-toggle");
const mainNav = document.querySelector(".main-nav");

if (mobileToggle && mainNav) {
  mobileToggle.addEventListener("click", () => {
    const isOpen = mainNav.classList.toggle("open");
    mobileToggle.setAttribute("aria-expanded", String(isOpen));
  });

  mainNav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      mainNav.classList.remove("open");
      mobileToggle.setAttribute("aria-expanded", "false");
    });
  });
}

const revealItems = document.querySelectorAll("[data-reveal]");

if (revealItems.length > 0) {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.2 }
  );

  revealItems.forEach((item) => observer.observe(item));
}

const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
const brandTrack = document.querySelector("[data-brand-track]");

if (brandTrack && reduceMotion.matches) {
  brandTrack.style.animation = "none";
}
