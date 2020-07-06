<?php declare(strict_types=1);
/**
 * @package RabbitMq\FirstTutorial
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace RabbitMq\FirstTutorial;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Publisher
 */
class Publisher
{
    /** @var AMQPStreamConnection */
    private AMQPStreamConnection $connection;

    /**
     * Sender constructor.
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'rabbitmq', 'rabbitmq');
    }

    public function __destruct()
    {
        try {
            $this->connection->close();
        } catch (\Exception $e) {
            //Do nothing.
        }
    }

    /**
     * @return void
     */
    function process(): void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        echo "[x] Sent 'Hello World!'\n";

        $channel->close();
    }
}
