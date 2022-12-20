<?php

$replaceStrings = [
	'namespace StartCompany\Component\StartComponent' => $namespacePartialString,
	'new stdClass()'                                  => 'new \stdClass()',
	'(Exception'                                      => '(\Exception',
	'new Exception'                                   => 'new \Exception',
	'new SimpleXMLElement('                           => 'new \SimpleXMLElement(',
	'new ReflectionObject('                           => 'new \ReflectionObject(',
	'StartComponent'                                  => $smallComponentName,
	'StartCompany'                                    => $companyNameSpace,
	'com_startcomponent'                              => "com_" . strtolower($componentName),

	//J4
	'Factory::getDbo()'                               => "Factory::getContainer()->get('DatabaseDriver')",
	'Factory::getUser()'                              => "Factory::getApplication()->getIdentity()",
	"Factory::getLanguage()"                          => "Factory::getApplication()->getLanguage()",
	"Factory::getDocument()"                          => "Factory::getApplication()->getDocument()",
	"Factory::getConfig()"                            => "Factory::getApplication()->getConfig()",
	"Factory::getSession()"                           => "Factory::getApplication()->getSession()",
];