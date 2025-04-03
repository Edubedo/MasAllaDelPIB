<?php
class Posts{   
    private $postTable = 'posts';
    private $postVotesTable = 'post_votes';
    private $dbConnect;
    
    public function __construct($db) {
        $this->dbConnect = $db;
    }
    
    public function isUserAlreadyVoted($user_id, $post_id) {
        $sqlQuery = "SELECT COUNT(*) as count FROM {$this->postVotesTable} WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }  
    
    public function getPostVotes($post_id) {
        $sqlQuery = "SELECT vote_up, vote_down FROM {$this->postTable} WHERE Id_posts = :post_id";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->execute([':post_id' => $post_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }   
    
    public function updatePostVote($postVoteData) {
        $sqlQuery = "UPDATE {$this->postTable} SET vote_up = :vote_up, vote_down = :vote_down WHERE Id_posts = :post_id";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->execute([
            ':vote_up' => $postVoteData['vote_up'],
            ':vote_down' => $postVoteData['vote_down'],
            ':post_id' => $postVoteData['post_id']
        ]);

        $sqlVoteQuery = "INSERT INTO {$this->postVotesTable} (post_id, user_id, date) VALUES (:post_id, :user_id, NOW())";
        $stmt = $this->dbConnect->prepare($sqlVoteQuery);
        if (!$stmt->execute([
            ':post_id' => $postVoteData['post_id'],
            ':user_id' => $postVoteData['user_id']
        ])) {
            die(json_encode(["error" => "Error al registrar el voto en post_votes"]));
        }
        return true;
    }
}
?>