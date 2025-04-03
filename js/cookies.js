window.onload = function() {
    setTimeout(function() {
        fetch('./views/layout/cookies.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('cookiesContainer').innerHTML = data;
            });
    }, 10000); /* 10000 milisegundos = 10 segundos */
};