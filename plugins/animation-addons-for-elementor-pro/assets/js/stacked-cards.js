(function ($) {
  const WCF_Stacked_Cards = function ($scope, $) {
    const wrapper = $scope.find('.aae--stacked-card-wrapper');
    const animStartPosition = wrapper.attr('data-start-pos');
    const animRotation = wrapper.attr('data-rotation');
    const container = $scope.find('.aae--stacked-cards');
    const items = container.find('.aae--stacked-card').toArray();
    let tl = gsap.timeline({
      scrollTrigger: {
        trigger: container[0],
        pin: true,
        scrub: 1,
        start: animStartPosition,
        end: () => "+=" + items.length * 400
      }
    });
    gsap.set(items, {
      transformPerspective: 1000,
      transformStyle: "preserve-3d",
      backfaceVisibility: "hidden"
    });
    items.forEach((item, i) => {
      const scaleVal = 0.75 + i * 0.05;
      const topOffset = i * 10;
      gsap.set(item, {
        top: topOffset,
        yPercent: 0,
        scaleX: 1,
        rotationX: 0,
        z: 0,
        transformOrigin: "bottom center"
      });
      if (i !== 0) {
        tl.from(item, {
          yPercent: 140,
          rotationX: animRotation,
          z: -100,
          duration: 2,
          transformOrigin: "bottom center",
          ease: "none"
        }, `-=${2.3}`);
      }
      if (i !== items.length - 1) {
        tl.to(item, {
          top: topOffset,
          rotationX: -60,
          scaleX: scaleVal,
          z: -100,
          duration: 1.5,
          transformOrigin: "top center",
          ease: "none"
        }, "+=2.5");
      }
    });
  };
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/aae--stacked-cards.default', WCF_Stacked_Cards);
  });
})(jQuery);