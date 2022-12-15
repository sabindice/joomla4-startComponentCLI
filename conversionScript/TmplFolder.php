<?php
$directory = dirname(__DIR__).'/tmpl';
$files     = array_diff(scandir($directory), array('.', '..', 'TmplFolder.php'));

require_once ($conversionFunctionScriptPath);

foreach ($files as $file)
{
	if ($file !== 'index.html')
	{
		if (!is_dir("$directory/$file"))
		{
			echo $file . '</br>';
		}
		else
		{
			if (file_exists("$directory/$file/index.html"))
			{
				unlink("$directory/$file/index.html");
			}
			if (file_exists("$directory/$file/view.html.php"))
			{
				unlink("$directory/$file/view.html.php");
			}
			if (file_exists("$directory/$file/tmpl/index.html"))
			{
				unlink("$directory/$file/tmpl/index.html");
			}

			if (file_exists("$directory/$file/tmpl/default.php"))
			{
				rename("$directory/$file/tmpl/default.php", "$directory/$file/default.php");
			}

			if (file_exists("$directory/$file/tmpl/edit.php"))
			{
				rename("$directory/$file/tmpl/edit.php", "$directory/$file/edit.php");
			}

			if (file_exists("$directory/$file/tmpl/modal.php"))
			{
				rename("$directory/$file/tmpl/modal.php", "$directory/$file/modal.php");
			}

			$hasDefaultXML = false;

			if (file_exists("$directory/$file/tmpl/default.xml"))
			{
				rename("$directory/$file/tmpl/default.xml", "$directory/$file/default.xml");
				$hasDefaultXML = true;
			}

			rmdir("$directory/$file/tmpl");

			if(!$hasDefaultXML){
				$singleNoMenuViews[] = $file;
			}

			$checkFilesArr = ['default.php', 'edit.php', 'modal.php'];

			foreach ($checkFilesArr as $item)
			{
				if (file_exists("$directory/$file/$item"))
				{
					$current = file_get_contents("$directory/$file/$item");

					//remove
					$current = str_replace($linesToRemove, '', $current);

					//replace
					$current = strtr($current, $replaceStrings);

					//replace special code
					$current = strtr($current, $replaceSpecialStrings);

					if (!str_contains($current, 'Site\Class\jbaLoader'))
					{
						if (strpos($current, "jbaLoader::getInstance();"))
						{
							$current = insert($current, "use Joomla\CMS\Language\Text;", "use StartCompany\Component\Jbacrm\Site\Class\jbaLoader;");
							$current = insert($current, "use StartCompany\Component\Jbacrm\Site\Class\jbaLoader;", "use StartCompany\Library\C3BaseView\c3baseView;");

							//replace
							$current = strtr($current, $replaceStrings);

							//replace special code
							$current = strtr($current, $replaceSpecialStrings);
						}
						file_put_contents("$directory/$file/$item", $current);
					}
				}
			}
		}

	}
}
echo 'TMPL Folder -> Files Done..' . PHP_EOL;