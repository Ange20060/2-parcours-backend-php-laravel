<?php

declare(strict_types=1);


interface Logger { public function log(string $message): void; }

class ConsoleLogger implements Logger
{
    public function log(string $message): void { echo "[LOG] $message" . PHP_EOL; }
}

class LoggerNul implements Logger
{
    public function log(string $message): void { }
}

class CommandeService
{
    public function __construct(private Logger $logger) {}

    public function traiter(): void
    {
        $this->logger->log("Commande traitée.");
    }
}

(new CommandeService(new ConsoleLogger()))->traiter();
(new CommandeService(new LoggerNul()))->traiter();
