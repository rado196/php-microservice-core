<?php

namespace System\Core\Microservice\Adapters;

use System\Core\Microservice\Base\AbstractMicroserviceProtocolAdapter;
use GuzzleHttp\Client as GuzzleClient;


final class MicroserviceProtocolHttpAdapter extends AbstractMicroserviceProtocolAdapter
{
  private $connection = null;

  /**
   * connect via guzzle on instance creation.
   */
  public function __construct($currentMicroservice)
  {
    parent::__construct($currentMicroservice);
    $this->connection = new GuzzleClient();
  }

  /**
   * send data to another microservice.
   */
  public function send($microservice, $url, $data = [])
  {
    $fullUrl = 'https://api.' . $microservice . '.example.com/internal' . $url;
    $response = $this->connection->request('POST', $fullUrl, [
      'form_params' => $data
    ]);

    $result = $response->getBody()->getContents();
    return json_decode($result, true);
  }
}
