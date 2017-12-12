<?php

/**
 * Example of retrieving an authentication token of the Kakao service
 *
 * PHP version 5.4
 *
 * @author     Onion Jeong <trauma2u@gmail.com>
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use OAuth\OAuth2\Service\Kakao;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

/**
 * Bootstrap the example
 */
require_once __DIR__.'/bootstrap.php';

// Session storage
$storage = new Session();

// Setup the credentials for the requests
$credentials = new Credentials(
    $servicesCredentials['kakao']['key'],
    $servicesCredentials['kakao']['secret'],
    $currentUri->getAbsoluteUri()
);

// Instantiate the Kakao service using the credentials, http client and storage mechanism for the token
/** @var $kakaoService Kakao */
$kakaoService = $serviceFactory->createService('kakao', $credentials, $storage);

if (!empty($_GET['code'])) {
    // retrieve the CSRF state parameter
    $state = isset($_GET['state']) ? $_GET['state'] : null;

    // This was a callback request from kakao, get the token
    $token = $kakaoService->requestAccessToken($_GET['code'], $state);

    // Send a request with it
    try {
        $response = $kakaoService->request('https://kapi.kakao.com/v1/user/me');
        $data = json_decode($response);
        if ($data) {
            foreach ($data as $key => $value) {
                if (is_object($value)) {
                    foreach ($value as $key2 => $value2) {
                        echo "[$key:$key2] $value2<br>";
                    }
                } else {
                    echo "[$key] $value<br>";
                }
            }
        }
    } catch (\Exception $e) {
        //
    }
} elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
    $url = $kakaoService->getAuthorizationUri();
    header("Location: $url");
} else {
    $url = $currentUri->getRelativeUri().'?go=go';
    echo "<a href=\"$url\">Login with Kakao!</a>";
}
