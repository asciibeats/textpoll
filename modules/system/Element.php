<?php
namespace modules\system;

use modules\system\Response;

abstract class Element
{
  protected $response;

  public function __construct(Response &$response)
  {
    $this->response = $response;
  }

  public function validate()
  {
    return true;
  }

  public function capture()
  {
  }

  public function bubble()
  {
  }
}
?>
