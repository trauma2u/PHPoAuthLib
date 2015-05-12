<?php

/**
 * Example of retrieving an authentication token of the Naver service
 *
 * PHP version 5.4
 *
 * @author     Onion Jeong <trauma2u@gmail.com>
 * @copyright  Copyright (c) 2015 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use OAuth\OAuth2\Service\Naver;
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
    $servicesCredentials['naver']['key'],
    $servicesCredentials['naver']['secret'],
    $currentUri->getAbsoluteUri()
);

// Instantiate the Naver service using the credentials, http client and storage mechanism for the token
/** @var $naverService Naver */
$naverService = $serviceFactory->createService('naver', $credentials, $storage, array());

if (!empty($_GET['code'])) {
    // This was a callback request from naver, get the token
    $token = $naverService->requestAccessToken($_GET['code']);

    // Send a request with it
    $xml = simplexml_load_string($naverService->request('https://apis.naver.com/nidlogin/nid/getUserProfile.xml'));

    // Show the resultant data
    if ((string)$xml->result->resultcode === '00') {
        foreach ($xml->response->children() as $key => $value) {
            echo "[$key] $value", PHP_EOL;
        }
        echo "[{$xml->result->resultcode}] {$xml->result->message}", PHP_EOL;
    } else {
    }
} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    $url = $naverService->getAuthorizationUri();
    header('Location: ' . $url);
} else {
    $url = $currentUri->getRelativeUri() . '?go=go';
    echo "<a href=\"$url\">Login with Naver!</a>";
}
