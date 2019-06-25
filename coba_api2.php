<?php
require __DIR__ . '/vendor/autoload.php';
$client = new \Google_Client();
$client->setApplicationName('coba api');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS_READONLY]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__.'/credentials2.json');
$service = new Google_Service_Sheets($client);
$spreadsheetId = '11i6ghiytWv23Tc1BWNKw9XGhSSya3HMT0vGGb9Hwibk';
$range = 'Form Responses 1';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

print($values);
 ?>
