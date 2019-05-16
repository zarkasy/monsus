<?php
require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

// mengambil nilai dari form pcl
function get_from_pcl(){
  // Get the API client and construct the service object.
  $client = getClient();
  $service = new Google_Service_Sheets($client);
  $spreadsheetId = '1PLMQLSV2tVlMLVrfmeJsZy19F4NgUQt1YHVjPDmuNtc';
  $range = 'Form Responses 1';
  $response = $service->spreadsheets_values->get($spreadsheetId, $range);
  $values = $response->getValues();
  return $values;
}

// mengambil nilai dari form pml
function get_from_pml(){
  // Get the API client and construct the service object.
  $client = getClient();
  $service = new Google_Service_Sheets($client);
  $spreadsheetId = '1rMDy_r0f531EVwzb5bYC5ohzrPs7FYgMdS-JwbFIGMo';
  $range = 'Form Responses 1';
  $response = $service->spreadsheets_values->get($spreadsheetId, $range);
  $values = $response->getValues();
  return $values;
}

// mengambil nilai dari form editor
function get_from_editor(){
  // Get the API client and construct the service object.
  $client = getClient();
  $service = new Google_Service_Sheets($client);
  $spreadsheetId = '1xa9-dQJytnwAI90SJLxUeVwFAxy86JMx7lJ2kB4POb0';
  $range = 'Form Responses 1';
  $response = $service->spreadsheets_values->get($spreadsheetId, $range);
  $values = $response->getValues();
  return $values;
}

echo get_from_pml();
