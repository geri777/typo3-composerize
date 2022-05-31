<?php

$typo3version = '9.5.29';
$packageStates = require ('typo3conf/PackageStates.php');
$strg_composer = '
{
    "name": "typo3project",
    "description": "automated generated composer file",
    "license": [
        "unlicensed"
    ],
    "authors": [
        {
            "name": "My Name",
            "email": "me@domain.tld"
        }
    ],
    "repositories": [
        {
            "type": "composer",
            "url": "https://composer.typo3.org/"
        },
        {
            "type": "path",
            "url": "./packages/*"
        }
    ],
    "require": {
        "typo3/cms-core": "' . $typo3version . '",';

foreach($packageStates['packages'] As $k=>$p) {
	$json = file_get_contents(rtrim($p['packagePath'], '/') . '/composer.json');
	$composer_data = json_decode($json);
    $name = $composer_data->name ? $composer_data->name : $k;
    $version = substr($name, 0, strlen('typo3/cms-')) == 'typo3/cms-' ? $typo3version : ($composer_data->version ? $composer_data->version : '*');
	$strg_composer .= "\r\n" . '        "' . $name . '": "' . $version . '",';
}
$strg_composer = rtrim($strg_composer, ',') . "\r\n" . '    }' . "\r\n" . '}' . "\r\n";

if(!file_exists('composer.json')) {
    file_put_contents('composer.json', $strg_composer);
} else {
    echo('composer.json already exists and will not be overwritten.');
}
echo('<pre>' . $strg_composer . '</pre>');
