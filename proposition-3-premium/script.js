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
    node.style.setProperty("--delay", `${Math.min(index * 80, 360)}ms`);
    observer.observe(node);
  });
}

const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
const marqueeTrack = document.querySelector("[data-marquee-track]");

if (marqueeTrack && reduceMotion.matches) {
  marqueeTrack.style.animation = "none";
}
