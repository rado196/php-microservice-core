<?php

use System\Core\Microservice\MicroserviceProtocol;

// ------------------------------------------------------------
// test http protocol
MicroserviceProtocol::factoryHttp('admin')
  ->send('users', '/api/users/890423', [
    'firstName' => 'John',
    'secondName' => 'Smith',
  ]);

// ------------------------------------------------------------
// test redis protocol
MicroserviceProtocol::factoryRedis('admin')
  ->listen('update-user', function ($data) {
    // ...
  });

MicroserviceProtocol::factoryRedis('admin')
  ->send('users', 'update-user', [
    'id' => 890423,
    'firstName' => 'John',
    'secondName' => 'Smith',
  ]);
