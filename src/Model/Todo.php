<?php

namespace App\Model;

use App\Core\Database;
use DateTime;
use DateTimeImmutable;
use PDO;

class Todo
{
    private int $id;
    private string $title;
    private ?string $deadline;
    private bool $isDone;
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Todo
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Todo
    {
        $this->title = $title;
        return $this;
    }

    public function getDeadline(): ?string
    {
        return $this->deadline;
    }

    public function setDeadline(?string $deadline): Todo
    {
        $this->deadline = $deadline;
        return $this;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): Todo
    {
        $this->isDone = $isDone;
        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): Todo
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function getAllUndone() : array
    {
        $conn = Database::getInstance()->getConnection();
        $query = "
            SELECT id, title, deadline, is_done, created_at
            FROM todos
            WHERE is_done = 0
            ORDER BY deadline ASC
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $todo = new Todo();
            $todo->setId($row['id'])
            ->setTitle($row['title'])
            ->setDeadline($row['deadline'] ? (new DateTime($row['deadline']))->format('Y-m-d') : null)
            ->setIsDone($row['is_done'])
            ->setCreatedAt((new DateTime($row['created_at']))->format('Y-m-d H:i:s'));
            $result[] = $todo;
        }
        return $result;
    }

    public static function add(string $title, ?string $dueDate): void
    {
        $conn = Database::getInstance()->getConnection();
        $query = "
            INSERT INTO todos (title, deadline, is_done)
            VALUES (?, ?, 0)
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute([$title, $dueDate]);
    }

    public static function setDone(int $id) : bool
    {
        $conn = Database::getInstance()->getConnection();
        $query = "
            UPDATE todos SET is_done = 1 WHERE id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function getDeadlineDiff(): ?string
    {
        if ($this->deadline === null) {
            return null;
        }

        $deadline = date_create_from_format('Y-m-d', $this->deadline);
        $today = date_create(date('Y-m-d'));
        $diff = (int)date_diff($today, $deadline)->format('%r%a');

        return match ($diff) {
            -1 => 'Wczoraj',
            0 => 'Dzisiaj',
            1 => 'Jutro',
            default => $diff > 0 ? "Za {$diff} dni" : "{$diff} dni temu",
        };
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function splitByDeadline(array $todos): array
    {
        $today = new DateTimeImmutable('today');

        $pastOrToday = [];
        $future = [];

        foreach ($todos as $todo) {
            $deadline = new DateTimeImmutable($todo->getDeadline());

            if ($deadline <= $today) {
                $pastOrToday[] = $todo;
            } else {
                $future[] = $todo;
            }
        }

        return [
            'pastOrToday' => $pastOrToday,
            'future' => $future,
        ];
    }
}
