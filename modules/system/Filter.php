<?php
namespace modules\system;

abstract class Filter
{
  abstract public function executeOn(Response &$response);
}
?>
