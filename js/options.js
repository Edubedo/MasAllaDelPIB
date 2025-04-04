$(document).ready(function() {
    $(".options").on("click", function(){
        var Id_posts = $(this).attr("id");
        Id_posts = Id_posts.replace(/\D/g,'');
        var vote_type = $(this).data("vote-type"); 
        $.ajax({                
            type : 'POST',
            url  : 'vote.php',
            dataType:'json',
            data : {Id_posts:Id_posts, vote_type:vote_type},
            success : function(response){
                    $("#vote_up_count_"+response.Id_posts).html("  "+response.vote_up);               $("#vote_down_count_"+response.Id_posts).html("  "+response.vote_down);                
            }
        });
    });
});