<?php
namespace modules\system;

class Response
{
  private $parameters;
  private $filters;

  public function __construct()
  {
    $this->parameters = array();
    $this->filters = new FilterChain();
  }

  public function addFilter(Filter $filter)
  {
    $this->filters->add($filter);
  }

  public function getParameterNames()
  {
    return array_keys($this->parameters);
  }

  public function issetParameter($name)
  {
    return isset($this->parameters[$name]);
  }

  public function getParameter($name)
  {
    return $this->parameters[$name];
  }

  public function setParameter($name, $value)
  {
    $this->parameters[$name] = $value;
  }

  public function unsetParameter($name)
  {
    unset($this->parameters[$name]);
  }

  public function flush()
  {
    $this->filters->executeOn($this);
		http_response_code($this->parameters['_code']);

    /*foreach ($this->headers as $name => $content)
    {
      header($name.': '.$content);
    }*/

		echo $this->parameters['_markup'];
  }
}
?>
