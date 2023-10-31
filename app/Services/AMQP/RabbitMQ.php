<?php

namespace App\Services\AMQP;

use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ implements AMQPInterface
{

    protected $connection  = null;
    protected $channel = null;

    public function __construct()
    {
        if ($this->connection) {
            return;
        }

        $this->connection = new AMQPStreamConnection( "rabbitmq", 5672,"guest","guest", "/");
        $this->channel = $this->connection->channel();

    }

    public function producer(string $queue, array $payload, string $exchange): void
    {
        $this->channel->queue_declare($queue, true, true, false);
        $message = new AMQPMessage(json_encode($payload), [ 'content_type' => $exchange]);
        $this->channel->basic_publish($message, $exchange, "rota");

    }

    public function getMessageCount(string $queueName): int
    {
        $queueDetails = $this->channel->queue_declare($queueName, passive: true);
        return $queueDetails[1];
    }

    public function peekMessage(string $queueName): ?AMQPMessage
    {
        $message = $this->channel->basic_get($queueName, $no_ack = true); 
        return $message;
    }
    public function acknowledgeMessage(AMQPMessage $message): void
    {
        $this->channel->basic_ack($message->getDeliveryTag());
    }

    public function producerFanout(string $queue, array $payload, string $exchange): void
    {
    }
    public function consumer(string $queue, string $exchange, Closure $callback): void
    {
    }

    private function closeChanel(): void
    {
        $this->connection->close();
    }

    private function closeConnection(): void
    {
        $this->channel->close();
    }
}
