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
 * Class Consumer
 */
class Consumer
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

    public function start()
    {
        $channel = $this->connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        echo " [*] Waiting for messages. CTRL+C to stop.\n";

        $callback = function (AMQPMessage $message) {
            $this->consume($message);
        };

        $channel->basic_consume(
            'hello',
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        while ($channel->is_consuming()) {
            try {
                $channel->wait();
            } catch (\ErrorException $e) {
            }
        }
    }

    /**
     * @param AMQPMessage $message
     *
     * @return void
     */
    public function consume(AMQPMessage $message): void
    {
        echo ' [x] Received ', $message->body, "\n";
    }
}