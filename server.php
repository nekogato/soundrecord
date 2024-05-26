<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php';

class Chat implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Handle incoming messages
        echo "New message: $msg\n";
        $from->send("Message received: $msg");
    }

    public function onClose(ConnectionInterface $conn) {
        // Handle closed connection
        echo "Connection closed! ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Handle error
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$app = new Ratchet\App('0.0.0.0', 8080);
$app->route('/chat', new Chat, ['*']);
$app->run();