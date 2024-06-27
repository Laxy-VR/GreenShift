<?php
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class LoggerGreenshift extends AbstractProcessingHandler
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function write(LogRecord $record): void
    {
        $insertLogs = $this->pdo->prepare("INSERT INTO logger(channel,level,message,time) VALUES (:channel,:level,:message,:time)");
        $insertLogs->execute(array(
            ':channel' => $record->channel,
            ':level' => $record->level->getName(),
            ':message' => $record->message,
            ':time' => $record->datetime
        ));
    }
} ?>