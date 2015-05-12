<?php

/**
 * Example of retrieving an authentication token of the Daum service
 *
 * PHP version 5.4
 *
 * @author     Onion Jeong <trauma2u@gmail.com>
 * @copyright  Copyright (c) 2015 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use OAuth\OAuth2\Service\Daum;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

/**
 * Bootstrap the example
 */
require_once __DIR__ . '/bootstrap.php';

// Session storage
$storage = new Session();

// Setup the credentials for the requests
$credentials = new Credentials(
    $servicesCredentials['daum']['key'],
    $servicesCredentials['daum']['secret'],
    $currentUri->getAbsoluteUri()
);

// Instantiate the Daum service using the credentials, http client and storage mechanism for the token
/** @var $daumService Daum */
$daumService = $serviceFactory->createService('daum', $credentials, $storage, array());

if (!empty($_GET['code'])) {
    // This was a callback request from daum, get the token
    $token = $daumService->requestAccessToken($_GET['code']);

    // Send a request with it
    $json = json_decode($daumService->request('/user/v1/show'));

    // Show the resultant data
    if ((string)$json->code === '200') {
        foreach ($json->result as $key => $value) {
            echo "[$key] $value", PHP_EOL;
        }
    } else {
        echo "[{$json->code}] {$json->message}", PHP_EOL;
    }
} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    $url = $daumService->getAuthorizationUri();
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
    echo "<a href=\"$url\">Login with Daum!</a>";
}
