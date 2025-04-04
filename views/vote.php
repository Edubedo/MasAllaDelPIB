<?php
include ('../config/database.php'); // Conexión a la base de datos
include ('megusta.php');
$posts = new Posts(); 

if($_POST['Id_posts'] && $user_creation) { 
    $postVote = $posts->getPostVotes($_POST['Id_posts']);

    if ($vote_type == 1) { // Like
        if (!$posts->isUserAlreadyVoted($user_creation, $_POST['Id_posts'])) {
            $postVote['vote_up'] += 1;  
        }   
    } else if ($vote_type == 0) { // Dislike
        if(!$posts->isUserAlreadyVoted($user_id, $_POST['post_id'])) {
            $postVote['vote_down'] += 1;                
        }  
    }
     
    $postVoteData = array(
        'post_id' => $_POST['post_id'],
        'user_id' => $user_id,
        'vote_up' => $postVote['vote_up'],
        'vote_down' => $postVote['vote_down'],
    );
    
    $postVoted = $posts->updatePostVote($postVoteData); 

    if($postVoted) {
        $response = array(
            'vote_up' => $postVote['vote_up'],
            'vote_down' => $postVote['vote_down'],
            'post_id' => $_POST['post_id']          
        );
        echo json_encode($response);
    }
}    
?>
 