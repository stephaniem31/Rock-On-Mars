<?php

namespace App\Model;

class ActivityManager extends AbstractManager
{
    public const TABLE = 'activity';

    /**
     * Insert new item in database
     */
    public function insert(array $activity): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, `image`,`localisation`,
        `start_at`,`end_at`, `activity_type`, `content`, `max_registered_members`, `member_id`) 
        VALUES (:name, :image, :localisation, :start_at, :end_at, :activity_type, :content,
        :max_registered_members, :member_id)");
        $statement->bindValue(':name', $activity['name'], \PDO::PARAM_STR);
        $statement->bindValue(':image', $activity['image'], \PDO::PARAM_STR);
        $statement->bindValue(':localisation', $activity['localisation'], \PDO::PARAM_STR);
        $statement->bindValue(':start_at', $activity['start_at'], \PDO::PARAM_STR);
        $statement->bindValue(':end_at', $activity['end_at'], \PDO::PARAM_STR);
        $statement->bindValue(':activity_type', $activity['activity_type'], \PDO::PARAM_STR);
        $statement->bindValue(':content', $activity['content'], \PDO::PARAM_STR);
        $statement->bindValue(':max_registered_members', $activity['max_registered_members'], \PDO::PARAM_INT);
        $statement->bindValue(':member_id', $activity['member_id'], \PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $activity): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name,
        `image` = :image, `localisation` = :localisation,`start_at` = :start_at,`end_at` = :end_at,
        `activity_type` = :activity_type, `content` = :content, `max_registered_members` = :max_registered_members,
        `member_id` = :member_id,
         WHERE id=:id");
         $statement->bindValue(':name', $activity['name'], \PDO::PARAM_STR);
         $statement->bindValue(':image', $activity['image'], \PDO::PARAM_STR);
         $statement->bindValue(':localisation', $activity['localisation'], \PDO::PARAM_STR);
         $statement->bindValue(':start_at', $activity['start_at'], \PDO::PARAM_STR);
         $statement->bindValue(':end_at', $activity['end_at'], \PDO::PARAM_STR);
         $statement->bindValue(':activity_type', $activity['activity_type'], \PDO::PARAM_STR);
         $statement->bindValue(':content', $activity['content'], \PDO::PARAM_STR);
         $statement->bindValue(':max_registered_members', $activity['max_registered_members'], \PDO::PARAM_INT);
         $statement->bindValue(':member_id', $activity['member_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function selectAllByActivityType($activityType)
    {

        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE activity_type =:activityType");
        $statement->bindValue(':activityType', $activityType, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectOneAndJoinMemberById($activity)
    {
        $statement = $this->pdo->prepare("SELECT a.*, m.name as creator_name FROM " . static::TABLE . " a
        JOIN member m ON m.id = :member_id");
        $statement->bindValue(':member_id', $activity['member_id'], \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectAllByMemberId($memberId)
    {

        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE member_id = :memberId");
        $statement->bindValue(':memberId', $memberId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectLast3Activities()
    {

        $query = "SELECT * FROM " . static::TABLE . " ORDER BY id DESC LIMIT 3";

        return $this->pdo->query($query)->fetchAll();
    }
}
