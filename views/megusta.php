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
        $sqlQuery = "SELECT Id_posts, user_creation, vote FROM {$this->postVotesTable} WHERE user_creation = ? AND Id_posts = ?";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return $result->num_rows;
    }
    
    public function getPostVotes($Id_posts) {
        $sqlQuery = "SELECT Id_posts, vote_up, vote_down FROM {$this->postTable} WHERE Id_posts = ?";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return  $row;     
    }   
    
    public function updatePostVote($postVoteData) {
        // Actualizar votos en la tabla posts
        $sqlQuery = "UPDATE {$this->postTable} SET vote_up = ?, vote_down = ? WHERE post_id = ?";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->bind_param("iii", $postVoteData['vote_up'], $postVoteData['vote_down'], $postVoteData['post_id']);
        $stmt->execute();
        
        // Insertar el voto en post_votes
        $sqlVoteQuery = "INSERT INTO {$this->postVotesTable} (post_id, user_id, vote, date) VALUES (?, ?, ?, NOW())";
        $stmt = $this->dbConnect->prepare($sqlVoteQuery);
        $stmt->bind_param("iii", $postVoteData['post_id'], $postVoteData['user_id'], $postVoteData['vote']);
        return $stmt->execute();
    }
}
?>