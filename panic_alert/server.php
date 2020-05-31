<?php

	use Ratchet\MessageComponentInterface;
	use Ratchet\ConnectionInterface;
	use Handular\Handular;

    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/Handular/Handular.php';

    Handular::command('message', 'Workers/Message@Message');

	class MyChat implements MessageComponentInterface {
	    protected $clients;

	    public function __construct() {
	        $this->clients = new \SplObjectStorage;
    		die('Connection Started');
	    }

	    public function onOpen(ConnectionInterface $conn) {
	        $this->clients->attach($conn);
	    }

	    public function onMessage(ConnectionInterface $from, $msg) {
	        Handular::manage($msg);
	    }

	    public function onClose(ConnectionInterface $conn) {
	        $this->clients->detach($conn);
	    }

	    public function onError(ConnectionInterface $conn, \Exception $e) {
	        $conn->close();
	    }
	}

    $app = new Ratchet\App('localhost', 8080);
    $app->route('/chat', new MyChat);
    $app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
    $app->run();