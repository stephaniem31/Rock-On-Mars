<?php

namespace App\Model;

class GatheringManager extends AbstractManager
{
    public const TABLE = 'gathering';

    public function insert(array $idArray): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`member_id`, `activity_id`) 
        VALUES (:member_id, :activity_id)");
        $statement->bindValue('member_id', $idArray['memberid'], \PDO::PARAM_INT);
        $statement->bindValue('activity_id', $idArray['activityid'], \PDO::PARAM_INT);

        $statement->execute();

        return (int)$this->pdo->lastInsertId();
    }

    public function selectAllParticipantsbyActivityId($id)
    {
        $statement = $this->pdo->prepare("SELECT member_id FROM " . static::TABLE . " 
        WHERE activity_id = :id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    public function selectJoinedActivitybymemberId($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE .
        " as g LEFT JOIN activity as a ON g.activity_id = a.id" .
        " WHERE g.member_id = :id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
