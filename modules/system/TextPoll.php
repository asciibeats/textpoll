<?php
namespace modules\system;

class TextPoll
{
  public static function start()
  {
		spl_autoload_register('self::autoload');
		$controller = new Response();
		//$controller->addFilter(new filter\AccessFilter());
		$controller->addFilter(new filter\TemplateFilter());
		$controller->flush();
  }

  private static function autoload($class)
  {
    require getcwd().DIRECTORY_SEPARATOR.strtr($class, '\\', DIRECTORY_SEPARATOR).'.php';
  }
}
?>
