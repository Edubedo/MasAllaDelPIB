<?php
class Posts
{
    private $postTable = 'posts';
    private $postVotesTable = 'likes';
    private $dbConnect;

    public function __construct($db)
    {
        $this->dbConnect = $db;
    }

    public function getPosts()
    {
        $sqlQuery = "SELECT Id_posts, title, content, post_date, category, image, user_creation, status, referencia_posts, vote_up, vote_down FROM {$this->postTable}";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isUserAlreadyVoted($user_creation, $Id_posts)
    {
        $sqlQuery = "SELECT Id_posts FROM {$this->postVotesTable} WHERE user_creation = :user_creation AND Id_posts = :Id_posts";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->execute([
            ':user_creation' => $user_creation,
            ':Id_posts' => $Id_posts
        ]);
        return $stmt->rowCount() > 0;
    }

    public function getPostVotes($Id_posts)
    {
        $sqlQuery = "SELECT Id_posts, vote_up, vote_down FROM" . $this->postTable . " WHERE Id_posts = '" . $Id_posts . "'";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->execute([':Id_posts' => $Id_posts]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePostVote($postVoteData)
    {
        if ($this->isUserAlreadyVoted($postVoteData['user_creation'], $postVoteData['Id_posts'])) {
            return false; // No permite votos duplicados
        }
        $sqlQuery = "UPDATE {$this->postTable} SET vote_up = :vote_up, vote_down = :vote_down WHERE Id_posts = :Id_posts";
        $stmt = $this->dbConnect->prepare($sqlQuery);
        $stmt->execute([
            ':vote_up' => $postVoteData['vote_up'],
            ':vote_down' => $postVoteData['vote_down'],
            ':Id_posts' => $postVoteData['Id_posts']
        ]);

        // Registrar el voto en la tabla de likes
        $sqlVoteQuery = "INSERT INTO {$this->postVotesTable} (Id_posts, user_creation, id_fecha_creacion) VALUES (:Id_posts, :user_creation, NOW())";
        $stmt = $this->dbConnect->prepare($sqlVoteQuery);
        return $stmt->execute([
            ':Id_posts' => $postVoteData['Id_posts'],
            ':user_creation' => $postVoteData['user_creation']

        ]);
    }
}
