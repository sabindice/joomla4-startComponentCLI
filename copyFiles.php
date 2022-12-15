<?php
/** Example of calling
 * Normal: php components/com_startcomponent/copyFiles.php
 * No rsync: php components/com_startcomponent/copyFiles.php noRsync
 * No Conversion: php components/com_startcomponent/copyFiles.php noConversion
 * !!! You can use both in php arguments : php components/com_startcomponent/copyFiles.php noRsync noConversion
 */

/** @var $noRsync - will not run rsync commands */
/** @var $noConversion - will not run rsync commands */

$noRsync      = false;
$noConversion = false;

foreach ($argv as $item)
	${$item} = true;

//this needs to be changed for every component
// START CONFIGURATION MODIFICATION
$companyNameSpace = 'StartCompany';
$componentName    = 'componentName'; //like content/contact/users
$sourcePath       = '/Users/USER/DEVELOPER/project/components/';
// END CONFIGURATION MODIFICATION

$copyComponentPath      = $sourcePath . 'com_' . strtolower($componentName);
$namespacePartialString = 'namespace ' . $companyNameSpace . '\\Component\\' . ucfirst(strtolower($componentName));
$smallComponentName     = ucfirst(strtolower($componentName));
$tableComponentName  = $smallComponentName;
// !!!! for table replacement this needs to be changed
$tableComponentName[3] = strtoupper($tableComponentName[3]);

//conversion scripts
$conversionFunctionScriptPath = __DIR__ . '/conversionScript/conversionFunction.php';

//for no notice
$linesToRemove = [];
require_once(__DIR__ . '/conversionScript/removeLine.php');

$replaceStrings = [];
require_once(__DIR__ . '/conversionScript/replaceString.php');

//this file is for special string replacement
$replaceSpecialStrings = [];
if(file_exists(__DIR__.'/conversionScript/replaceSpecialString.php')){
	require_once(__DIR__ . '/conversionScript/replaceSpecialString.php');
}

//CONFIGURATION
$viewPath       = __DIR__ . '/src/View';
$tmplPath       = __DIR__ . '/tmpl';
$modelPath      = __DIR__ . '/src/Model';
$controllerPath = __DIR__ . '/src/Controller';
$helperPath     = __DIR__ . '/src/Helper';
$formsPath      = __DIR__ . '/forms';
$fieldsPath     = __DIR__ . '/fields';
$assetsPath     = __DIR__ . '/assets';
$languagePath   = __DIR__ . '/language';

//exclude txt
$modelExcludes = __DIR__ . '/excludeModelFiles.txt';

$singleNoMenuViews = [];

if (!$noRsync)
{
	echo 'Copying files to assets' . PHP_EOL;
	exec("rsync -av --exclude='index.html' $copyComponentPath/assets/ $assetsPath");
	echo '..........................' . PHP_EOL;
	sleep(1);

	echo 'Copying files to fields' . PHP_EOL;
	exec("rsync -av --exclude='index.html' $copyComponentPath/models/fields/ $fieldsPath");
	echo '..........................' . PHP_EOL;
	sleep(1);

	echo 'Copying files to forms' . PHP_EOL;
	exec("rsync -av --exclude='index.html' $copyComponentPath/models/forms/ $formsPath");
	echo '..........................' . PHP_EOL;
	sleep(1);

	echo 'Copying files to language' . PHP_EOL;
	exec("rsync -av --exclude='index.html' $copyComponentPath/language/ $languagePath");
	echo '..........................' . PHP_EOL;
	sleep(1);

	echo 'Copying files to src/View' . PHP_EOL;
	exec("rsync -av --exclude='index.html' $copyComponentPath/views/ $viewPath");
	echo '..........................' . PHP_EOL;
	sleep(1);

	echo 'Copying files to tmpl folder' . PHP_EOL;
	exec("rsync -av --exclude='index.html' $copyComponentPath/views/ $tmplPath");
	echo '..........................' . PHP_EOL;
	sleep(1);

	echo 'Copying files to src/Model folder' . PHP_EOL;
	exec("rsync -av --exclude-from='$modelExcludes' $copyComponentPath/models/ $modelPath");
	echo '..........................' . PHP_EOL;
	sleep(1);

	echo 'Copying files to src/Controller folder' . PHP_EOL;
	exec("rsync -av --exclude='index.html' $copyComponentPath/controllers/ $controllerPath");
	echo '..........................' . PHP_EOL;
	sleep(1);
}

if (!$noConversion)
{
	require(__DIR__ . '/conversionScript/ViewFolder.php');
	echo '..........................' . PHP_EOL;
	sleep(1);

	require(__DIR__ . '/conversionScript/TmplFolder.php');
	echo '..........................' . PHP_EOL;
	sleep(1);

	require(__DIR__ . '/conversionScript/ModelFolder.php');
	echo '..........................' . PHP_EOL;
	sleep(1);

	require(__DIR__ . '/conversionScript/ControllerFolder.php');
	echo '..........................' . PHP_EOL;
	sleep(1);
}

if (!empty($singleNoMenuViews))
{
	echo 'NomenuRule -> conversion done ..' . PHP_EOL;
	$current = file_get_contents(__DIR__ . '/src/Service/StartComponentNomenuRules.php');
	$current = str_replace("['&views&']", json_encode($singleNoMenuViews), $current);

	//replace
	$current = strtr($current, $replaceStrings);

	//replace special code
	$current = strtr($current, $replaceSpecialStrings);

	file_put_contents(__DIR__ . '/src/Service/StartComponentNomenuRules.php', $current);
	$tmpName = $smallComponentName . 'NomenuRules.php';
	rename(__DIR__ . '/src/Service/StartComponentNomenuRules.php', __DIR__ . '/src/Service/' . $tmpName);
	echo '..........................' . PHP_EOL;
	sleep(1);
}

if (file_exists($copyComponentPath . '/helpers/' . $componentName . '.php'))
{
	echo 'Helper  -> conversion done ..' . PHP_EOL;
	require_once ($conversionFunctionScriptPath);
	$finalHelperName = $smallComponentName.'Helper.php';
	mkdir($helperPath);
	exec("rsync -av --exclude='index.html' $copyComponentPath/helpers/$componentName.php $helperPath/$finalHelperName ");

	$current = file_get_contents($helperPath.'/'.$finalHelperName);

	//namespace
	if(strpos($current, "*/")){
		$current = insert($current, "*/", "namespace StartCompany\Component\StartComponent\Site\Helper;");
	}

	//replace
	$current = strtr($current, $replaceStrings);

	//replace special code
	$current = strtr($current, $replaceSpecialStrings);

	file_put_contents($helperPath.'/'.$finalHelperName, $current);
	echo '..........................' . PHP_EOL;
	sleep(1);
}

echo 'Display controller -> conversion done ..' . PHP_EOL;
$current = file_get_contents(__DIR__ . '/src/Controller/DisplayController.php');

//replace
$current = strtr($current, $replaceStrings);
//replace special code
$current = strtr($current, $replaceSpecialStrings);

file_put_contents(__DIR__ . '/src/Controller/DisplayController.php', $current);
echo '..........................' . PHP_EOL;
sleep(1);

echo 'Router -> conversion done ..' . PHP_EOL;
$current = file_get_contents(__DIR__ . '/src/Service/Router.php');
//replace
$current = strtr($current, $replaceStrings);
//replace special code
$current = strtr($current, $replaceSpecialStrings);
file_put_contents(__DIR__ . '/src/Service/Router.php', $current);

require(__DIR__ . '/conversionScript/getNoMenuViews.php');
echo '..........................' . PHP_EOL;
sleep(1);

echo "ALL DONE! ENJOY!!!";