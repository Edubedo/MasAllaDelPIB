<?php
class Posts{   
    private $postTable = 'posts';
    private $postVotesTable = 'likes';
    private $dbConnect;
    
    public function __construct($db) {
        $this->dbConnect = $db;
    } 

    public function getPosts(){
        $sqlQuery = "SELECT Id_posts, title, content, post_date, category, image, user_creation, status, referencia_posts, vote_up, vote_down FROM {$this->postTable}";
        $resultado = mysqli_query($this->dbConnect, $sqlQuery);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];      
    }

    public function isUserAlreadyVoted($user_creation, $Id_posts) {
        $sqlQuery = 'SELECT Id_posts, user_creation, vote FROM' .$this->postVotesTable." WHERE user_creation = '".$user_creation."' AND Id_posts = '".$Id_posts."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return $result->num_rows;
    }
    
    public function getPostVotes($Id_posts) {
        $sqlQuery = 'SELECT Id_posts, vote_up, vote_down FROM' .$this->postTable." WHERE Id_posts = '".$Id_posts."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return  $row;     
    }   
    
    public function updatePostVote($postVoteData) {
        $sqlQuery = "UPDATE ".$this->postTable." SET vote_up = '".$postVoteData['vote_up']."' , vote_down = '".$postVoteData['vote_down']."' WHERE Id_posts = '".$postVoteData['Id_posts']."'";
        mysqli_query($this->dbConnect, $sqlQuery);      
        
        $sqlVoteQuery = "INSERT INTO ".$this->postVotesTable." (id_like, Id_posts, user_creation, id_fecha_creacion) VALUES ('', '".$postVoteData['Id_posts']."', '".$postVoteData['user_creation']."',now())";
        if($sqlVoteQuery) {
            mysqli_query($this->dbConnect, $sqlVoteQuery);  
            return true;            
        }   
    }
}
?>