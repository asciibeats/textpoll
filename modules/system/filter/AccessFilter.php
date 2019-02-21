<?php
namespace modules\system\filter;

use modules\system\Response;

class AccessFilter extends \modules\system\Filter
{
  public function executeOn(Response &$response)
  {
    if (($_GET['user'] == 'tilla') && ($_GET['pass'] == 'pass'))
    {
      $response->setParameter('_user', $_GET['user']);
      $response->setParameter('_groups', array('admin', 'publish'));
    }
  }
}
?>
