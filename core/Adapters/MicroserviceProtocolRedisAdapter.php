<?php

namespace System\Core\Microservice\Adapters;

use System\Core\Microservice\Base\AbstractMicroserviceProtocolAdapter;
use Redis;


final class MicroserviceProtocolRedisAdapter extends AbstractMicroserviceProtocolAdapter
{
  private $connection = null;
  private $listeners = [];

  private function establishConnection()
  {
    return new Redis([
      'host' => '127.0.0.1',
      'port' => 6379,
      'connectTimeout' => 2.5,
      'auth' => ['phpredis', 'phpredis'],
      'ssl' => ['verify_peer' => false],
      'backoff' => [
        'algorithm' => Redis::BACKOFF_ALGORITHM_DECORRELATED_JITTER,
        'base' => 500,
        'cap' => 750,
      ],
    ]);
  }


  /**
   * connect via redis on instance creation.
   */
  public function __construct($currentMicroservice)
  {
    parent::__construct($currentMicroservice);
    $this->connection = $this->establishConnection();
  }

  /**
   * listen when ata received from another microservice.
   */
  public function listen($event, callable $callback)
  {
    $eventName = $this->getCurrentMicroservice() . ':' . $event;

    if (!isset($this->listeners[$eventName])) {
      $this->listeners[$eventName] = [];
      $this->connection->subscribe($eventName, function ($_redis, $_channel, $message) use ($eventName) {
        if (!isset($this->listeners[$eventName])) {
          return;
        }

        $data = json_encode($message, true);
        foreach ($this->listeners[$eventName] as $callback) {
          $callback($data);
        }
      });
    }

    $this->listeners[$eventName][] = $callback;
  }

  /**
   * send data to another microservice.
   */
  public function send($microservice, $event, $data = [])
  {
    $this->connection->publish($microservice . ':' . $event, json_encode($data));
  }
}
