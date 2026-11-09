// (function ($) {
//   const scrollMotionCards = function ($scope, $) {
//     gsap.registerPlugin(ScrollTrigger);

//     let toolkitContainer = document.querySelector(".aae-card-area");

//     let toolkitCards = gsap.utils.toArray(".toolkit-card-anim");

//     let toolkitMm = gsap.matchMedia();

//     if (toolkitContainer && toolkitCards) {
//       toolkitMm.add("(min-width: 1200px)", () => {
//         if (toolkitContainer && toolkitCards) {
//           let cardTl = gsap.timeline({
//             scrollTrigger: {
//               trigger: toolkitContainer,
//               start: "top top",
//               end: "bottom bottom",
//               // markers: true,
//               toggleActions: "restart none reverse none",
//             },
//           });

//           toolkitCards.forEach((card, index) => {
//             if (index % 2 == 0 && index !== 0) {
//               cardTl.from(card, {
//                 x: "-110vw",
//                 duration: 0.2,
//                 ease: "expo.out",
//               });
//             } else if (index !== 0)
//               cardTl.from(card, {
//                 x: "110vw",
//                 duration: 0.2,
//                 ease: "expo.out",
//               });
//           });

//           let toolkitTitlesTop = gsap.utils.toArray(".toolkit-top-title");
//           let toolkitTitlesBottom = gsap.utils.toArray(".toolkit-bottom-title");

//           let cardUnstackTl = gsap.timeline({
//             scrollTrigger: {
//               trigger: toolkitContainer,
//               start: "top 20%",
//               end: "bottom -=800",
//               pin: true,
//               scrub: true,
//               pinspacing: false,
//             },
//           });

//           let currentTitle = toolkitTitlesTop[0];
//           let currentBottomTitle = toolkitTitlesBottom[0];
//           if (toolkitTitlesTop && toolkitTitlesBottom && toolkitCards) {
//             for (let i = toolkitCards.length - 1, j = 0; i > 0; i--, j++) {
//               const commonAnimationProps = {
//                 ease: "power3.in",
//                 // immediateRender: true,
//                 duration: 0,
//               };

//               cardUnstackTl.to(
//                 toolkitCards[i],
//                 {
//                   y: "-150vh",
//                   rotation: 0,
//                   ease: "none",
//                 },
//                 i === toolkitCards.length - 1 ? 0 : ">.2"
//               );

//               cardUnstackTl.to(
//                 currentTitle,
//                 {
//                   y: "-400%",
//                   ...commonAnimationProps,
//                 },
//                 `-=${0.3}`
//               );

//               cardUnstackTl.fromTo(
//                 toolkitTitlesTop[j + 1],
//                 { y: "400%" },
//                 {
//                   y: "0%",
//                   ...commonAnimationProps,
//                 },
//                 `-=${0.3}`
//               );

//               cardUnstackTl.to(
//                 currentBottomTitle,
//                 {
//                   y: "-400%",
//                   ...commonAnimationProps,
//                 },
//                 `-=${0.3}`
//               );

//               cardUnstackTl.fromTo(
//                 toolkitTitlesBottom[j + 1],
//                 { y: "400%" },
//                 {
//                   y: "0%",
//                   ...commonAnimationProps,
//                 },
//                 `-=${0.3}`
//               );

//               currentTitle = toolkitTitlesTop[j + 1];
//               currentBottomTitle = toolkitTitlesBottom[j + 1];
//             }
//           }
//         }
//       });
//     }
//   };
//   $(window).on("elementor/frontend/init", function () {
//     elementorFrontend.hooks.addAction(
//       "frontend/element_ready/aae--nested-scrollmotion.default",
//       scrollMotionCards
//     );
//   });
// })(jQuery);

// console.log('aae-nested-scrollmotion running')