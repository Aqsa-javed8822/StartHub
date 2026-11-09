document.addEventListener("DOMContentLoaded", function () {
  if (window.ScrollToPlugin) {
    gsap.registerPlugin(ScrollToPlugin);
  }
  let targets = document.querySelectorAll('.aae-scroll-to, a');
  if (targets.length && typeof gsap === "object") {
    targets.forEach(function (el) {
      el.addEventListener("click", function (e) {
        e.preventDefault();
        gsap.to(window, {
          duration: 1,
          scrollTo: {
            y: 'body',
            offsetY: 70
          }
        });
      });
    });
  }
});