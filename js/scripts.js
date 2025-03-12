$(document).ready(function () {
  $('.post-wrapper').slick({
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 2000,
      speed: 800, // Reduce la velocidad de transición
      cssEase: "linear", // Hace la animación más fluida
      centerPadding: '0px',
      nextArrow: $('.next'),
      prevArrow: $('.prev'),
      centerMode: true, // Asegura que el último slide se ajuste
      variableWidth: false, // Evita que los slides tengan ancho variable
      responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1
            }
        }
    ]
  });
});

