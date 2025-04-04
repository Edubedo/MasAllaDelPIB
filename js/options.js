$(document).ready(function() {
    $(".options").click(function() {  // <- Agregamos el evento click
        var $this = $(this);
        if ($this.hasClass("disabled")) return;
        
        $this.addClass("disabled"); // Deshabilita el botón temporalmente
        var Id_posts = $this.attr("id").replace(/\D/g, '');
        var vote_type = $this.data("vote-type"); 
        
        $.ajax({                
            type : 'POST',
            url  : 'vote.php',
            dataType: 'json',
            data : {Id_posts: Id_posts, vote_type: vote_type},
            success : function(response){
                if (response && response.vote_up !== undefined && response.vote_down !== undefined) {
                    $("#vote_up_count_" + response.Id_posts).html(" " + response.vote_up);
                    $("#vote_down_count_" + response.Id_posts).html(" " + response.vote_down);
                }
                $this.removeClass("disabled"); // Habilita el botón después de la respuesta
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX:", error);
                $this.removeClass("disabled"); // Habilita el botón en caso de error
            }
        });
    });
});