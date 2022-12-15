<?php

$replaceStrings = [
	'namespace StartCompany\Component\StartComponent' => $namespacePartialString,
	'new stdClass()' => 'new \stdClass()',
	'(Exception' => '(\Exception',
	'new Exception' => 'new \Exception',
	'new SimpleXMLElement(' => 'new \SimpleXMLElement(',
	'new ReflectionObject(' => 'new \ReflectionObject(',
	'StartComponent' => $smallComponentName,
	'StartCompany' => $companyNameSpace,
	'Factory::getDbo()' => "Factory::getContainer()->get('DatabaseDriver')",
	'com_startcomponent' => "com_".strtolower($componentName),
];