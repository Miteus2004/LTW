<?php

declare(strict_types=1);

class Message
{
    public float $id;
    public string $text;
    public string $date;
    public int $type;
    public int $sender;
    public int $product;
    public int $interested;





    public function __construct(int $id, string $text, string $date, int $type, int $sender, int $product, int $interested)
    {
        $this->id = $id;
        $this->text = $text;
        $this->date = $date;
        $this->type = $type;
        $this->sender = $sender;
        $this->product = $product;
        $this->interested = $interested;

    }


    static function getMessages(PDO $db): array
    {
        $stmt = $db->prepare('SELECT * FROM Message');
        $stmt->execute();

        $messages = array();
        while ($message = $stmt->fetch()) {
            $messages[] = new Message(
                $message['MessageId'],
                $message['Text'],
                $message['Date'],
                $message['Type'],
                $message['SenderId'],
                $message['ProductId'],
                $message['BuyerId'],
            );
        }

        return $messages;
    }

    static function getProductUsersInterested(PDO $db, int $product): array
    {
        $stmt = $db->prepare('
        SELECT m.*
FROM Message m
JOIN (
    SELECT BuyerId, MAX(Date) AS MaxDate
    FROM Message
    WHERE ProductId = ?
    GROUP BY BuyerId
) max_dates
ON m.BuyerId = max_dates.BuyerId AND m.Date = max_dates.MaxDate
WHERE m.ProductId = ?
ORDER BY m.Date DESC');
        $stmt->execute(array($product,$product));

        $messages = array();
        while ($message = $stmt->fetch()) {
            $messages[] = new Message(
                $message['MessageId'],
                $message['Text'],
                $message['Date'],
                $message['Type'],
                $message['SenderId'],
                $message['ProductId'],
                $message['BuyerId'],
            );
        }

        return $messages;
    }

    static function getProductUserMessages(PDO $db, int $product, int $interested): array
    {
        $stmt = $db->prepare('SELECT * FROM Message WHERE ProductId = ? AND BuyerId = ? ORDER BY Date ASC');
        $stmt->execute(array($product, $interested));

        $messages = array();
        while ($message = $stmt->fetch()) {
            $messages[] = new Message(
                $message['MessageId'],
                $message['Text'],
                $message['Date'],
                $message['Type'],
                $message['SenderId'],
                $message['ProductId'],
                $message['BuyerId'],
            );
        }

        return $messages;
    }

    static function newMessage(PDO $db, string $text, int $type, int $sender, int $product, int $interested): bool

    {
      $stmt = $db->prepare('INSERT INTO Message (Text, Date, Type, SenderId, ProductId, BuyerId) VALUES (?, ?, ?, ?, ?, ?)');
  
      if ($stmt->execute(array($text, date('Y-m-d H:i'), $type, $sender, $product, $interested))) return true;
      else return false;

    }

}
