<?php

namespace System\Core\Microservice\Base;

final class AbstractMicroserviceProtocolAdapter
{
  private $currentMicroservice = null;

  /**
   * set current microservice name.
   */
  public function __construct($currentMicroservice)
  {
    $this->currentMicroservice = $currentMicroservice;
  }

  protected function getCurrentMicroservice()
  {
    return $this->currentMicroservice;
  }
}
