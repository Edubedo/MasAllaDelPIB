function consulta_buscador(busqueda){

var data = 'buscar';

    var parametros = {"busqueda": busqueda, "data" : data};
    $.ajax({
    data:parametros,
    url:'../views/buscador.php',
    type:'POST',
    beforeSend:function(){
    

    console.log('Estoy ready');

    },

    success:function(data){
    console.log('ITS ok');

        if(busqueda == ''){
            document.getElementById("card_busqueda").style.opacity = 0;
        }else{
            document.getElementById("card_busqueda").style.transition = 'all 1s';
            document.getElementById("card_busqueda").style.opacity = 1;
        }

        document.getElementById("resultados_busqueda_nav").innerHTML = data;

    },
    error:function(data, error){
    console.log('Estoy malito');

    }
    });

}