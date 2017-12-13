PHPoAuthLib(KR)
===========
The Forked Project of [Lusitanian/PHPoAuthLib](https://github.com/Lusitanian/PHPoAuthLib) for Korean Services

Installation
------------
```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/trauma2u/PHPoAuthLib"
        }
    ],
    "require": {
        "lusitanian/oauth": "dev-master"
    }
}
```

Added Korean Services
---------------------
- Daum
- Kakao
- Naver

Sample Code
---------------------
/oauth/credentials.php
```php
return [
    'naver' => [
        'key' => 'naver key',
        'secret' => 'naver secret',
    ],
];
```
/oauth/naver.php
```php
// credentials
$serviceCredentials = require('credentials.php');
$credentials = new OAuth\Common\Consumer\Credentials(
    $serviceCredentials['naver']['key'], // key
    $serviceCredentials['naver']['secret'], // secret
    'https://onion.world/oauth/token.php' // redirect url
);

// storage
$storage = new OAuth\Common\Storage\Session();

// service
$serviceFactory = new OAuth\ServiceFactory();
/** @var $naverService OAuth\OAuth2\Service\Naver */
$naverService = $serviceFactory->createService('naver', $credentials, $storage);
```
/oauth/login.php
```html
<a href="/oauth/authorize.php">Login with Naver!</a>
```
/oauth/authorize.php
```php
require('naver.php');

// authorize
$url = $naverService->getAuthorizationUri();
header("Location: $url");
```
/oauth/token.php
```php
$code = isset($_GET['code']) ? $_GET['code'] : null;
$state = isset($_GET['state']) ? $_GET['state'] : null;
if (!$code) throw new \OAuth\Common\Exception\Exception();

require('naver.php');

// token
$token = $naverService->requestAccessToken($code, $state);

// request
$response = $naverService->request('/do/something');
$data = json_decode($response);
```

Extensions
---------------------
- [trauma2u/PHPoAuthUserData](https://github.com/trauma2u/PHPoAuthUserData)
