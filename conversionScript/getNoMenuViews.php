<?php

$directory = dirname(__DIR__) . '/tmpl';
$files     = array_diff(scandir($directory), array('.', '..'));

$singleNoMenuViews = [];
$tmpViews          = '';
foreach ($files as $file)
{
	$hasDefaultXML = false;

	if (file_exists("$directory/$file/default.xml"))
	{
		$hasDefaultXML = true;
	}

	if (!$hasDefaultXML)
	{
		$singleNoMenuViews[] = $file;
	}

	if (str_ends_with($file, 's'))
	{

		$var         = (string) $file;
		$singularVar = substr($var, 0, -1);

		$string   = <<<HRDOC
		\$$var = new RouterViewConfiguration('$var');
		\$this->registerView(\$$var);
		
		\$$singularVar = new RouterViewConfiguration('$singularVar');
		\${$singularVar}->setKey('id');
		\$this->registerView(\$$singularVar);
HRDOC;
		$tmpViews .= PHP_EOL . $string . PHP_EOL;
	}
}


if (strlen($tmpViews))
{
	$current = file_get_contents(dirname(__DIR__) . '/src/Service/Router.php');
	$current = str_replace('//&views&', $tmpViews, $current);

	file_put_contents(dirname(__DIR__) . '/src/Service/Router.php', $current);
}

