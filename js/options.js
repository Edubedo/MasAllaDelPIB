$(document).ready(function() {
    $(".options").on("click", function(){
        var post_id = $(this).attr("id");
        post_id = post_id.replace(/\D/g,'');
        var vote_type = $(this).data("vote-type"); 
        $.ajax({                
            type : 'POST',
            url  : 'vote.php',
            dataType:'json',
            data : {post_id:post_id, vote_type:vote_type},
            success : function(response){
                    $("#vote_up_count_"+response.post_id).html("  "+response.vote_up);               $("#vote_down_count_"+response.post_id).html("  "+response.vote_down);                
            }
        });
    });
});