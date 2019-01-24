<?php
namespace modules\system\filter;

use modules\system\Element;
use modules\system\Response;

class TemplateFilter extends \modules\system\Filter
{
  public function executeOn(Response &$response)
  {
		try
		{
			$response->setParameter('_code', 200);
			$response->setParameter('_markup', $this->insertTemplate($response, 'pages/'.$_GET['page']));
		}
		catch (\Exception $exception)
		{
			$response->setParameter('_message', $exception->getMessage());
			$response->setParameter('_code', $exception->getCode());
			$response->setParameter('_markup', $this->insertTemplate($response, 'error/'.$exception->getCode()));
		}
  }

  private function insertTemplate(Response &$response, string $page)
  {
		$path = getcwd().DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $page);
		$html = $path.'.html';

		if (!file_exists($html))
		{
			throw new \Exception('not found', 404);
		}

		$php = $path.'.php';

		if (file_exists($php))
		{
			include_once $php;
			$class = str_replace('/', '\\', $page);

			if (!class_exists($class))
			{
				throw new \Exception('class not found', 500);
			}

			$action = new $class($response);

			if (!$action instanceof Element)
			{
				throw new \Exception('not an action', 500);
			}

			if (!$action->validate())
			{
				throw new \Exception('invalid template', 500);
			}

			$action->capture();
		}

		$pattern = '#@(?>((?>if|for(?>each)?) *+\([^)]*+\))|(else(?>if)?(?> *+\([^)]*+\))?)|(end)|(element *+\'([^\']++)\')|(echo) *+((?>\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+|\'.*\'|\.)+))#u';
		$subject = trim(file_get_contents($html));
		$matches = array();

		preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE);
		#var_dump($matches); return '';

		$script = '$_result = \'';
		$start = 0;

		foreach ($matches as $match)
		{
			$end = $match[0][1];
			$script .= trim(mb_substr($subject, $start, $end - $start));
			$start = $end + mb_strlen($match[0][0]);

			if (isset($match[6]))//echo
			{
				$script .= '\'.'.$match[7][0].'.\'';
			}
			elseif (isset($match[4]))//element
			{
				$script .= $this->insertTemplate($response, 'elements/'.$match[5][0]);
			}
			elseif (isset($match[3]))//end
			{
				$script .= '\';}$_result.=\'';
			}
			elseif (isset($match[2]))//else(if)
			{
				$script .= '\';}'.$match[2][0].'{$_result.=\'';
			}
			else//if/for(each)
			{
				$script .= '\';'.$match[1][0].'{$_result.=\'';
			}
		}

		$end = mb_strlen($subject);
		$script .= trim(mb_substr($subject, $start, $end - $start));
		$script .= '\'; return $_result;';

		if (isset($action))
		{
			$action->bubble();
		}

		return eval($script);
  }
}
?>
