(function ($) {
  'use strict';

  var AaeHeroTextExplode = function ($scope, $) {
    var $wrapper = $scope.find('.aae-hero-text-explode');
    if (!$wrapper.length) return;
    var settingsData = $wrapper.data('settings');
    if (!settingsData) return;
    var sentences = settingsData.sentences || [];
    var entryDuration = settingsData.entryDuration || 0.7;
    var entryStagger = settingsData.entryStagger || 0.1;
    var explodeDelay = settingsData.explodeDelay || 1.5;
    var explodeDuration = settingsData.explodeDuration || 2.2;
    var gravity = settingsData.gravity || 800;
    var velocityMin = settingsData.velocityMin || 300;
    var velocityMax = settingsData.velocityMax || 600;
    var exitDelay = settingsData.exitDelay || 1.5;
    if (!sentences.length) return;
    gsap.registerPlugin(SplitText, Physics2DPlugin);
    var titleEl = $wrapper.find('.aae-hero-text-explode__title')[0];
    if (!titleEl) return;
    var widgetId = $wrapper.data('id');
    var accentId = 'aae-hte-accent-' + widgetId;
    var idx = 0;
    function renderSentence(s) {
      var html = '<span>' + s.pre + ' <span class="aae-hero-text-explode__accent" id="' + accentId + '">' + s.highlight + '</span>';
      if (s.post) {
        html += ' ' + s.post;
      }
      html += '</span>';
      titleEl.innerHTML = html;
    }
    function animateSentence() {
      var sentenceSplit = new SplitText(titleEl, {
        type: 'chars, words, lines',
        linesClass: 'aae-hte-line',
        wordsClass: 'aae-hte-word',
        charsClass: 'aae-hte-char'
      });
      gsap.from(sentenceSplit.words, {
        y: -100,
        opacity: 0,
        rotation: 'random(-80, 80)',
        duration: entryDuration,
        ease: 'back',
        stagger: entryStagger
      });
      var accentEl = document.getElementById(accentId);
      if (!accentEl) return;
      var highlightSplit = new SplitText(accentEl, {
        type: 'chars',
        charsClass: 'aae-hero-text-explode-blast-char'
      });
      gsap.set(accentEl, {
        opacity: 1
      });
      var tl = gsap.timeline({
        delay: 0.8
      });
      tl.to(highlightSplit.chars, {
        duration: explodeDuration,
        opacity: 0,
        rotation: 'random(-2000, 2000)',
        physics2D: {
          angle: 'random(240, 320)',
          velocity: 'random(' + velocityMin + ', ' + velocityMax + ')',
          gravity: gravity
        },
        stagger: 0.015,
        delay: explodeDelay
      }).to(sentenceSplit.words, {
        y: 100,
        opacity: 0,
        rotation: 'random(-80, 80)',
        duration: entryDuration,
        ease: 'back',
        stagger: entryStagger,
        delay: exitDelay
      });
      tl.call(function () {
        sentenceSplit.revert();
        highlightSplit.revert();
        idx = (idx + 1) % sentences.length;
        renderSentence(sentences[idx]);
        animateSentence();
      });
    }
    renderSentence(sentences[idx]);
    animateSentence();
  };
  $(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/hero-text-explode.default', AaeHeroTextExplode);
  });
})(jQuery);