<?php
$directory = dirname(__DIR__).'/src/Model';
$files = array_diff(scandir($directory), array('.', '..', 'ModelFolder.php'));

require_once ($conversionFunctionScriptPath);

foreach ($files as $file)
{
	if (strpos($file, 'Model') === false) {
		$vars = explode('.', $file);
		$final = ucfirst($vars[0]).'Model.php';

		$current = file_get_contents($directory.'/'.$file);
		$substring = string_between_two_string($current, 'class', 'extends');
		$current = str_replace($substring,  ' '.ucfirst($vars[0]).'Model ', $current);

		//table
		$current = str_replace($tableComponentName.'Table', "Administrator", $current);
		$current = str_replace("return Table::getInstance(", "return parent::getTable(", $current);

		//namespace
		if(strpos($current, "*/")){
			$current = insert($current, "*/", "namespace StartCompany\Component\StartComponent\Site\Model;");
		}

		if(strpos($current, "jbaLoader::getInstance();")){
			$current = insert($current, "use Joomla\CMS\Factory;", "use StartCompany\Component\Jbacrm\Site\Class\jbaLoader;");
		}


		if(strpos($current, "new jbaDataModel()")){
			$current = insert($current, "use Joomla\CMS\Factory;", "use StartCompany\Component\Jbacrm\Site\Class\jbaDataModel;");
		}

		//remove
		$current = str_replace($linesToRemove, '', $current);

		//replace
		$current = strtr($current, $replaceStrings);

		//replace special code
		$current = strtr($current, $replaceSpecialStrings);

		// Write the contents back to the file
		file_put_contents($directory.'/'.$file, $current);
		rename($directory.'/'.$file, $directory.'/'.$final) ;
	}
}

echo 'src/Model Folder -> Files Done..' . PHP_EOL;


