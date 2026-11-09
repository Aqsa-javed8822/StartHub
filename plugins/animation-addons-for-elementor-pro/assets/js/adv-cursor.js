($ => {
  //wcf cursor
  const cursor = function () {
    const cursor_enable = elementorFrontend.getKitSettings("wcf_enable_cursor");
    if (!cursor_enable) {
      return;
    }
    const cursor = $(".wcf-cursor");
    const cursor_follower = $(".wcf-cursor-follower");
    const breakpoint = elementorBreakpoints[elementorFrontend.getKitSettings("wcf_cursor_breakpoint")].value;
    if ($(window).width() < breakpoint) {
      cursor.hide();
      cursor_follower.hide();
      return;
    }
    cursor.css("display", "flex");
    cursor_follower.show();
    gsap.set(cursor, {
      xPercent: -50,
      yPercent: -50,
      scale: 0
    });
    gsap.set(cursor_follower, {
      xPercent: -50,
      yPercent: -50,
      scale: 0
    });
    const setCursorX = gsap.quickTo(cursor, "x", {
      duration: 0.6,
      ease: "power4.out"
    });
    const setCursorFollowerX = gsap.quickTo(cursor_follower, "x", {
      duration: 0.6,
      ease: "power4.out"
    });
    const setCursorY = gsap.quickTo(cursor, "y", {
      duration: 0.6,
      ease: "power4.out"
    });
    const setCursorFollowerY = gsap.quickTo(cursor_follower, "y", {
      duration: 0.6,
      ease: "power4.out"
    });
    const tl = gsap.timeline({
      paused: true
    });
    tl.to(cursor, {
      scale: 1,
      opacity: 1,
      duration: 0.5,
      ease: "power4.out"
    });
    tl.to(cursor_follower, {
      scale: 1,
      opacity: 1,
      duration: 0.5,
      ease: "power4.out"
    });
    $(document).mousemove(function (e) {
      tl.play();
      setCursorX(e.clientX);
      setCursorY(e.clientY);
      setCursorFollowerX(e.clientX);
      setCursorFollowerY(e.clientY);
    });
  };
  cursor();
})(jQuery);