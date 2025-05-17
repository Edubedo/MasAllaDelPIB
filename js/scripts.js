$(document).ready(function () {
    $('.post-wrapper').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 900, // Velocidad más lenta para transición más visible
        cssEase: "cubic-bezier(0.455, 0.03, 0.515, 0.955)", // Transición más sofisticada
        centerPadding: '0px',
        nextArrow: $('.next'),
        prevArrow: $('.prev'),
        centerMode: true, // Activado para mejor efecto carrusel
        variableWidth: false,
        adaptiveHeight: false,
        pauseOnHover: true,
        edgeFriction: 0, // Sin fricción para transición perfecta
        touchThreshold: 8,
        waitForAnimate: true, // Esperar a que termine la animación antes de responder a clics repetidos
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    centerMode: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    centerMode: true
                }
            }
        ]
    });
});

