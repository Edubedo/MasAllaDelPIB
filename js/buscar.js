function consulta_buscador(busqueda){

var data = 'busacr';

    var parametros = {"busqueda": busqueda, "data" : data};
    $_ajax({
    data:parametros,
    url:'',
    type:'POST',
    beforeSend:function(){
    



    },

    success:function(data){



    },
    error:function(data, error){

    }
    });



}