(function ($) {
  function LottieScrollTrigger(vars) {
    let speed_number = parseFloat(vars.speed);
    let playhead = {
        frame: vars.startFrameOffset || 0
      },
      target = gsap.utils.toArray(vars.target)[0],
      speeds = speed_number > 200 ? `+=${speed_number}` : "+=1000",
      st = {
        trigger: vars.trigger_selector || target,
        pin: true,
        start: "top center",
        end: speeds,
        endTrigger: vars.endTrigger || null,
        // ✅ Add this line
        scrub: 1
      },
      ctx = gsap.context && gsap.context(),
      animation = lottie.loadAnimation({
        container: target,
        renderer: "svg",
        loop: false,
        autoplay: false,
        path: vars.path,
        rendererSettings: vars.rendererSettings || {
          preserveAspectRatio: "xMidYMid slice"
        }
      }),
      frameAnimation;
    for (let p in vars) {
      // let users override the ScrollTrigger defaults
      st[p] = vars[p];
    }
    frameAnimation = vars.timeline || gsap.timeline({
      scrollTrigger: st
    });
    if (vars.timeline && !vars.timeline.vars.scrollTrigger) {
      // if the user passed in a timeline that didn't have a ScrollTrigger attached, create one.
      st.animation = frameAnimation;
      ScrollTrigger.create(st);
    }
    animation.addEventListener("DOMLoaded", function () {
      let createTween = function () {
        animation.goToAndStop(playhead.frame, true);
        frameAnimation.to(playhead, {
          frame: animation.totalFrames - 1 - (vars.endFrameOffset || 0),
          ease: "none",
          duration: frameAnimation.duration() || 1,
          onUpdate: () => {
            animation.goToAndStop(playhead.frame, true);
          }
        }, vars.sequence ? ">" : 0);
        return () => animation.destroy && animation.destroy();
      };
      ctx && ctx.add ? ctx.add(createTween) : createTween();
      // in case there are any other ScrollTriggers on the page and the loading of this Lottie asset caused layout changes
      ScrollTrigger.sort();
      ScrollTrigger.refresh();
    });
    animation.frameAnimation = frameAnimation;
    return animation;
  }
  var Wcf_Lottie = function ($scope, $) {
    if (typeof lottie === 'undefined') {
      console.warn('Lottie is not loaded.');
      return;
    }
    let dataset = $scope.find('.aae-lottie-wrp')[0].dataset;
    let source = dataset.src;
    let loop = dataset.loop;
    let autoplay = dataset.autoplay;
    let count = parseInt(dataset.count) || 0;
    let speed = parseInt(dataset.speed) || 1;
    let duration = parseInt(dataset.duration) || 0.5;
    let hover = dataset.hover;
    let direction = dataset.direction;
    let renderer = dataset.renderer || 'svg'; // Default to 'svg' if not specified
    let settings = JSON.parse($scope.find('.aae-lottie-wrp').first().attr('data-settings'));
    if (!source) {
      console.warn('Lottie source is not defined.');
      return;
    }
    loop = typeof count === 'number' ? count : loop === '1';
    let s_id = '#wcf-lottie-player-' + $scope.attr('data-id');
    let lottelement = document.querySelector(s_id);
    if (settings.event === 'scroll') {
      LottieScrollTrigger({
        target: s_id,
        trigger_selector: dataset.triggerselector || '',
        endTrigger: dataset.endtriggerselector || null,
        // ✅ Add this line  
        startFrameOffset: parseInt(settings.start_point) || 0,
        endFrameOffset: parseInt(settings.end_point) || 0,
        path: source,
        speed: speed,
        pin: false,
        // ⛔ No freeze
        scrub: 2
      });
      return;
    }
    const player_element = lottie.loadAnimation({
      container: lottelement,
      // DOM element
      renderer: renderer,
      loop: typeof count === 'number' ? count : loop === '1',
      autoplay: 'viewport' == settings.event || settings.play == 'inview' ? true : false,
      path: source,
      rendererSettings: {
        preserveAspectRatio: 'xMidYMid meet'
      }
    });
    if (!player_element) {
      return;
    }
    player_element.addEventListener('DOMLoaded', () => {
      if (speed > 0) {
        player_element.setSpeed(speed);
      }
      let observer = new IntersectionObserver(function (entries, observer) {
        for (let entry of entries) {
          if (entry.isIntersecting && ('viewport' == settings.event || settings.play == 'inview')) {
            player_element.play();
            console.log('Animation started');
          }
        }
      });
      observer.observe(lottelement);
      $scope[0].addEventListener("click", () => {
        if (settings.event === 'click') {
          player_element.play();
        }
        if (settings.pause === 'onclick') {
          player_element.pause();
        }
      });
      $scope[0].addEventListener("mouseout", () => {
        if (settings.pause === 'onmouseleave') {
          player_element.pause();
        }
      });
      $scope[0].addEventListener("mouseover", () => {
        if (settings.play === 'onhover') {
          player_element.play();
        }
      });
      const totalFrames = player_element.totalFrames;
      let playhead = {
        frame: 0
      };
      if ("cursor_move" == settings.event) {
        $scope[0].addEventListener('mousemove', e => {
          const rect = $scope[0].getBoundingClientRect();
          const mouseX = e.clientX - rect.left;
          const relX = mouseX / rect.width;
          const clampedX = Math.max(0, Math.min(1, relX));
          const targetFrame = clampedX * totalFrames;
          gsap.to(playhead, {
            frame: targetFrame,
            // target frame number
            duration: duration || 0.5,
            ease: 'power2.out',
            onUpdate: () => {
              player_element.goToAndStop(playhead.frame, true); // manually update Lottie frame
            }
          });
        });
      }
      if (settings.event === 'hover') {
        // $scope[0].addEventListener("mouseover", () => {               
        //    player_element.play();                
        // });  
        playhead = {
          frame: totalFrames
        };
        $scope[0].addEventListener("mouseover", () => {
          gsap.to(playhead, {
            frame: 0,
            duration: duration || 0.5,
            ease: "power2.out",
            onUpdate: () => {
              player_element.goToAndStop(playhead.frame, true);
            },
            onComplete: () => {
              player_element.play();
            }
          });
        });
      }
    });
  };

  // Make sure you run this code under Elementor.
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/wcf--lottie-animation.default', Wcf_Lottie);
  });
})(jQuery);