<?php

namespace System\Core\Microservice;

use System\Core\Microservice\Adapters\MicroserviceProtocolRedisAdapter;
use System\Core\Microservice\Adapters\MicroserviceProtocolHttpAdapter;


final class MicroserviceProtocol
{
  public const ADAPTER_REDIS = 'protocol:redis-adapter';
  public const ADAPTER_HTTP = 'protocol:http-adapter';

  private static $instances = [];

  private static function factory($adapterName, callable $buildInstance)
  {
    if (!isset(self::$instances[$adapterName])) {
      self::$instances[$adapterName] = $buildInstance();
    }

    return self::$instances[$adapterName];
  }

  public static function factoryRedis(string $currentMicroservice): MicroserviceProtocolRedisAdapter
  {
    return self::factory(self::ADAPTER_HTTP, function () use ($currentMicroservice) {
      return new MicroserviceProtocolRedisAdapter($currentMicroservice);
    });
  }

  public static function factoryHttp(string $currentMicroservice): MicroserviceProtocolHttpAdapter
  {
    return self::factory(self::ADAPTER_HTTP, function () use ($currentMicroservice) {
      return new MicroserviceProtocolHttpAdapter($currentMicroservice);
    });
  }
}
