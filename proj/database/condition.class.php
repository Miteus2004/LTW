<?php

declare(strict_types=1);

class Condition
{
  public int $id;
  public string $name;




  public function __construct(int $id, string $name)
  {
    $this->id = $id;
    $this->name = $name;
  }


  static function getConditions(PDO $db): array
  {
    $stmt = $db->prepare('SELECT ConditionId, Name FROM Condition');
    $stmt->execute();

    $conditions = array();
    while ($condition = $stmt->fetch()) {
      $conditions[] = new Condition(
        $condition['ConditionId'],
        $condition['Name'],
      );
    }

    return $conditions;
  }

  static function getCondition(PDO $db, int $id): ?Condition
  {
    $stmt = $db->prepare('SELECT * FROM Condition WHERE ConditionId LIKE ?');
    $stmt->execute(array($id));

    $condition = $stmt->fetch();
    if ($condition) {

      return new Condition(
        $condition['ConditionId'],
        $condition['Name'],
      );
    }
    return null;
  }


  static function searchCondition(PDO $db, string $name): Condition
  {
    $stmt = $db->prepare('SELECT ConditionId, Name FROM Condition WHERE Name LIKE ?');
    $stmt->execute(array($name));

    $condition = $stmt->fetch();

    return new Condition(
      $condition['ConditionId'],
      $condition['Name'],
    );

  }


  static function newCondition(PDO $db): void
  {

  }

  static function deleteCondition(PDO $db): void
  {

  }


}
