<?php
namespace modules\system;

use modules\system\Response;

class FilterChain extends Filter
{
  protected $filters = array();

  public function add(Filter $filter)
  {
    $this->filters[] = $filter;
  }

  public function executeOn(Response &$response)
  {
    foreach ($this->filters as $filter)
    {
      $filter->executeOn($response);
    }
  }
}
?>
