<a href="http://travis-ci.org/alxsad/stack-ip-firewall"><img src="https://secure.travis-ci.org/alxsad/stack-ip-firewall.png" alt="Build
Status" style="max-width:100%;"></a>
<a href="https://packagist.org/packages/alxsad/stack-ip-firewall"><img src="https://poser.pugx.org/alxsad/stack-ip-firewall/v/stable.png" alt="Latest Stable Version" style="max-width:100%;"></a>

# IpFirewall middleware
[Stack](http://stackphp.com) middleware to filtering IP.

## Intallation
The recommended way to install this library is through [Composer](http://getcomposer.org/):
``` json
{
    "require": {
        "alxsad/stack-ip-firewall": "~1.0"
    }
}
```

## Usage
```php
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__ . '/../app/bootstrap.php.cache';
require_once __DIR__ . '/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

$stack = (new Stack\Builder())->push('Alxsad\Stack\IpFirewall', [
  '192.168.1.*',
  '192.168.10.10',
]);

$kernel = $stack->resolve($kernel);

Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
```

## License

This library is released under the MIT License. See the bundled LICENSE file for details.
