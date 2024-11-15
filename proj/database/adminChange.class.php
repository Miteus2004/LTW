<?php

declare(strict_types=1);

class AdminChange
{
    public int $userId;
    public string $description;




    public function __construct(int $userId, string $description)
    {
        $this->userId = $userId;
        $this->description = $description;
    }


    static function getChanges(PDO $db): array
    {
        $stmt = $db->prepare('SELECT UserId, Description FROM AdminChanges');
        $stmt->execute();

        $changes = array();
        while ($change = $stmt->fetch()) {
            $changes[] = new AdminChange(
                $change['UserId'],
                $change['Description'],
            );
        }

        return $changes;
    }




    static function newChange(PDO $db, $userId, $description): bool
    {
        $stmt = $db->prepare('INSERT INTO AdminChanges (Userid, Description) VALUES (?, ?)');
        if ($stmt->execute([$userId, $description]))
            return true;
        else
            return false;
    }
}
