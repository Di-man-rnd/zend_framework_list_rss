<?php

class DeleteController extends Zend_Controller_Action
{
    /**
     * форма для ввода названия новости
     */
    public function indexAction()
    {        
    }

    /**
     * сброс
     */
    public function resetAction()
    {
        /*
        Server	spotted-monkey.rmq.cloudamqp.com
        User & Vhost	tludhnil
        Password	su5VYlRqnhcu0AopdVQu4AbyIJq3_Vi9 
        URL	amqp://tludhnil:su5VYlRqnhcu0AopdVQu4AbyIJq3_Vi9@spotted-monkey.rmq.cloudamqp.com/tludhnil
        Protocols	✓ HTTPS (API)         ✓ AMQPS         ✓ STOMP         ✓ MQTT         ✗ WebSockets
        */
        $connection_params = array(
            'host' => 'localhost',
            'port' => 5672,
            'vhost' => '/',
            'login' => 'tludhnil',
            'password' => 'su5VYlRqnhcu0AopdVQu4AbyIJq3_Vi9'
        );
        $connection = new AMQPConnection($connection_params);
        $connection->connect();
        $channel = new AMQPChannel($connection);
        $exchange = new AMQPExchange($channel);
        $exchange->setName('ex_clean');
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare(); $queue = new AMQPQueue($channel);
        
        $queue->setName('messname');
        
        $queue->setFlags(AMQP_IFUNUSED | AMQP_AUTODELETE);
        $queue->declare();
        $result = $exchange->publish(json_encode("666---666"), "foo_key");
        $connection->disconnect();
        
        /*
        $connection = new AMQPConnection($connection_params);
        $connection->connect();
        $channel = new AMQPChannel($connection);
        $exchange = new AMQPExchange($channel);
        $exchange->setName('ex_clean');
        $exchange->setType(AMQP_EX_TYPE_FANOUT);
        $exchange->setFlags(AMQP_IFUNUSED | AMQP_AUTODELETE);
        $exchange->declare(); $queue = new AMQPQueue($channel);
        $queue->setName('messname');
        $queue->setFlags(AMQP_IFUNUSED | AMQP_AUTODELETE);
        $queue->declare();
        $queue->bind($exchange->getName(), 'foo_key');
        while (true) {
            if ($envelope = $queue->get(AMQP_AUTOACK)) {
                $message = json_decode($envelope->getBody());
                print($message);
            }
        }
        $connection->disconnect();
        */
        
    }


}



