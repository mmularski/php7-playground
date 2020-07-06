<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class FibonacciRpcClient
 */
class FibonacciRpcClient
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    /**
     * @var string
     */
    private $callbackQueue;

    /**
     * @var
     */
    private $response;

    /**
     * @var
     */
    private $corr_id;

    /**
     * FibonacciRpcClient constructor.
     *
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            'rabbitmq',
            5672,
            'rabbitmq',
            'rabbitmq'
        );

        $this->channel = $this->connection->channel();

        [$this->callbackQueue, ,] = $this->channel->queue_declare(
            "",
            false,
            false,
            true,
            false
        );

        $this->channel->basic_consume(
            $this->callbackQueue,
            '',
            false,
            true,
            false,
            false,
            [
                $this,
                'onResponse',
            ]
        );
    }

    /**
     * @param $rep
     *
     * @return void
     */
    public function onResponse($rep): void
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    /**
     * @param $n
     *
     * @return int
     *
     * @throws ErrorException
     */
    public function call($n): int
    {
        $this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            (string) $n,
            [
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callbackQueue,
            ]
        );

        $this->channel->basic_publish($msg, '', 'rpc_queue');

        while (!$this->response) {
            $this->channel->wait();
        }

        return intval($this->response);
    }
}

$fibonacci_rpc = new FibonacciRpcClient();
$response = $fibonacci_rpc->call(30);
echo ' [.] Got ', $response, "\n";