<?php
require_once ($conversionFunctionScriptPath);

function removeDirectory($path) {

	$files = glob($path . '/*');
	foreach ($files as $file) {
		is_dir($file) ? removeDirectory($file) : unlink($file);
	}
	rmdir($path);
}

$directory = dirname(__DIR__).'/src/View';
$files = array_diff(scandir($directory), array('.', '..', 'ViewFolder.php'));
foreach ($files as $key => $file)
{
	if(is_dir("$directory/$file") && ($file == ucwords($file)))
	{
		unset($files[$key]);
	}
}

foreach ($files as $file)
{
	if($file !== 'index.html'){
		removeDirectory("$directory/$file/tmpl");
		//rename the view.html.php
		rename("$directory/$file/view.html.php", "$directory/$file/HtmlView.php");
		if(file_exists("$directory/$file/index.html"))
		{
			unlink("$directory/$file/index.html");
		}

		$current = file_get_contents("$directory/$file/HtmlView.php");
		$substring = string_between_two_string($current, 'class', 'extends');
		$current = str_replace($substring, ' HtmlView ', $current);

		//namespace
		if(strpos($current, "*/")){
			$current = insert($current, "*/", "namespace StartCompany\Component\StartComponent\Site\View\NewName;");
		}

		//namespace
		if(strpos($current, "c3baseView")){
			$current = insert($current, "use Joomla\CMS\Factory;", "use StartCompany\Library\C3BaseView\c3baseView;");
		}

		if(strpos($current, "jbaLoader::getInstance();")){
			$current = insert($current, "use Joomla\CMS\Factory;", "use StartCompany\Component\Jbacrm\Site\Class\jbaLoader;");
		}

		//remove
		$current = str_replace($linesToRemove, '', $current);

		//replace
		$current = strtr($current, $replaceStrings);

		//replace special code
		$current = strtr($current, $replaceSpecialStrings);

		$current = str_replace("NewName", ucfirst($file), $current);
		file_put_contents("$directory/$file/HtmlView.php", $current);

		$newName = ucfirst($file);
		rename("$directory/$file", "$directory/$newName");
	}
}

echo 'View Folder -> Files Done..' . PHP_EOL;