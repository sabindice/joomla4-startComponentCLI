<?php
$directory = dirname(__DIR__).'/src/Controller';
$files = array_diff(scandir($directory), array('.', '..', 'ControllerFolder.php'));

require_once ($conversionFunctionScriptPath);

foreach ($files as $key => $file)
{
	if($file === ucwords($file))
	{
		unset($files[$key]);
	}
}

foreach ($files as $file)
{
	if (strpos($file, 'Controller') === false) {

		$vars = explode('.', $file);
		$final = ucfirst($vars[0]).'Controller.php';

		$current = file_get_contents($directory.'/'.$file);
		$substring = string_between_two_string($current, 'class', 'extends');
		$current = str_replace($substring, ' '.ucfirst($vars[0]).'Controller ', $current);

		if(strpos($current, "*/")){
			$current = insert($current, "*/", "namespace StartCompany\Component\StartComponent\Site\Controller;");
		}
		else{
			$current = insert($current, "<?php", "namespace StartCompany\Component\StartComponent\Site\Controller;");
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

		// Write the contents back to the file
		file_put_contents($directory.'/'.$file, $current);

		rename( $directory.'/'.$file, $directory.'/'.$final) ;
	}
}
echo 'src/Controller Folder -> Files Done..' . PHP_EOL;

