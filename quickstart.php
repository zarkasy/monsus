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
  $arr = array();
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

//mengambil jumlah record dari form pcl berdasarkan filter wilayah -> digunakan untuk membuat grafik
//input berupa string nama kabupaten, kecamatan, desa, dan nks
//output berupa angka numeric
function get_progres_pcl($kab, $kec = NULL, $desa = NULL, $nks = NULL){
  $values = get_from_pcl();
  if(!is_null($kab)){
    $filter_values_kab = array_filter($values, function($var) use ($kab){
      return (strpos($var[4],$kab)!==false);
    });
    if(!is_null($kec)){
      $filter_values_kec = array_filter($filter_values_kab, function($var) use ($kec){
        return (strpos($var[5],$kec)!==false);
      });
      if(!is_null($desa)){
        $filter_values_desa = array_filter($filter_values_kec, function($var) use ($desa){
          return (strpos($var[6],$desa)!==false);
        });
        if(!is_null($nks)){
          $filter_values_nks = array_filter($filter_values_desa, function($var) use ($nks){
            return (strpos($var[7],$nks)!==false);
          });
          $filter_values = count($filter_values_nks);
        }else{
          $filter_values = count($filter_values_desa);
        }
      }else{
        $filter_values = count($filter_values_kec);
      }
    }else{
      $filter_values = count($filter_values_kab);
    }
  }else{
    $filter_values = NULL;
  }
  return $filter_values;
}

//mengambil jumlah record dari form pml berdasarkan filter wilayah -> digunakan untuk membuat grafik
//input berupa string nama kabupaten, kecamatan, desa, dan nks
//output berupa angka numeric
function get_progres_pml($kab, $kec = NULL, $desa = NULL, $nks = NULL){
  $values = get_from_pml();
  if(!is_null($kab)){
    $filter_values_kab = array_filter($values, function($var) use ($kab){
      return (strpos($var[3],$kab)!==false);
    });
    if(!is_null($kec)){
      $filter_values_kec = array_filter($filter_values_kab, function($var) use ($kec){
        return (strpos($var[4],$kec)!==false);
      });
      if(!is_null($desa)){
        $filter_values_desa = array_filter($filter_values_kec, function($var) use ($desa){
          return (strpos($var[5],$desa)!==false);
        });
        if(!is_null($nks)){
          $filter_values_nks = array_filter($filter_values_desa, function($var) use ($nks){
            return (strpos($var[6],$nks)!==false);
          });
          $filter_values = count($filter_values_nks);
        }else{
          $filter_values = count($filter_values_desa);
        }
      }else{
        $filter_values = count($filter_values_kec);
      }
    }else{
      $filter_values = count($filter_values_kab);
    }
  }else{
    $filter_values = NULL;
  }
  return $filter_values;
}

//mengambil jumlah record dari form editor berdasarkan filter wilayah -> digunakan untuk membuat grafik
//input berupa string nama kabupaten, kecamatan, desa, dan nks
//output berupa angka numeric
function get_progres_editor($kab, $kec = NULL, $desa = NULL, $nks = NULL){
  $values = get_from_editor();
  if(!is_null($kab)){
    $filter_values_kab = array_filter($values, function($var) use ($kab){
      return (strpos($var[4],$kab)!==false);
    });
    if(!is_null($kec)){
      $filter_values_kec = array_filter($filter_values_kab, function($var) use ($kec){
        return (strpos($var[5],$kec)!==false);
      });
      if(!is_null($desa)){
        $filter_values_desa = array_filter($filter_values_kec, function($var) use ($desa){
          return (strpos($var[6],$desa)!==false);
        });
        if(!is_null($nks)){
          $filter_values_nks = array_filter($filter_values_desa, function($var) use ($nks){
            return (strpos($var[7],$nks)!==false);
          });
          $filter_values = count($filter_values_nks);
        }else{
          $filter_values = count($filter_values_desa);
        }
      }else{
        $filter_values = count($filter_values_kec);
      }
    }else{
      $filter_values = count($filter_values_kab);
    }
  }else{
    $filter_values = NULL;
  }
  return $filter_values;
}

// $values = get_from_pcl();
// $a = $values[4];
// $b = array_count_values($a);
// // print_r($values[4]);
// $coba = array_filter($values, function($var){
//   return (strpos($var[4],'Tana Tidung')!==false);
// });
// print_r($coba);
// $c = array_keys($b);
// for ($i=0;$i<=count($b);$i++){
//
// }
// echo $c[3];

// echo $b[2];
// $c = count($b);
// for ($i=0;$i<4;$i++){
//   for ($j=0;$j<4;$j++){
//
//   }
// }
