<?php
declare(strict_types=1);

class UserRepository
{
    public function __construct(private PDO $pdo) {}   // injection → faible couplage

    public function trouver(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function tous(): array
    {
        return $this->pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creer(string $nom, string $email): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email) VALUES (:n, :e)");
        $stmt->execute([':n' => $nom, ':e' => $email]);
        return (int) $this->pdo->lastInsertId();
    }
}
