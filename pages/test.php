<?php
namespace pages;

use modules\system\Element;

class test extends Element
{
  public function validate()
  {
    return true;
  }

  public function capture()
  {
    $this->response->setParameter('test', 'DIES IST EIN TEST');
  }

  public function bubble()
  {
  }
}
?>
