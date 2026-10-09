# App Libraries

Reusable utility classes and library functions.

## Available Libraries

### HttpClient

Simple HTTP client for GET and POST requests:

```php
use App\Libraries\HttpClient;

$client = new HttpClient('https://api.example.com');
$client->setHeader('Authorization', 'Bearer token');
$response = $client->get('/endpoint', ['param' => 'value']);
```

### UsdtFetcherPrice

Fetch USDT price from CoinGecko API:

```php
use App\Libraries\UsdtFetcherPrice;

$fetcher = new UsdtFetcherPrice();
$price = $fetcher->getCurrentPrice('usd');
$prices = $fetcher->getPriceInMultipleCurrencies(['usd', 'eur', 'gbp']);
$isStable = $fetcher->isUsdtStable();
```

### CacheManager

Simple file-based caching:

```php
use App\Libraries\CacheManager;

$cache = new CacheManager();
$cache->put('key', 'value', 60);
$value = $cache->get('key');
$cache->forget('key');
$cache->flush();
```

### PaginationHelper

Helper for pagination metadata:

```php
use App\Libraries\PaginationHelper;

$pagination = new PaginationHelper(1, 15);
$pagination->setTotal(100);
$metadata = $pagination->getMetadata();
```

### Validator

Utility validation methods:

```php
use App\Libraries\Validator;

Validator::email('test@example.com');
Validator::url('https://example.com');
Validator::numeric('123');
Validator::min('hello', 3);
Validator::uuid('550e8400-e29b-41d4-a716-446655440000');
Validator::phone('+1234567890');
```

### StringHelper

String manipulation utilities:

```php
use App\Libraries\StringHelper;

StringHelper::slug('Hello World');
StringHelper::camelCase('hello_world');
StringHelper::snakeCase('HelloWorld');
StringHelper::truncate('Long text', 10);
StringHelper::contains('hello', 'ell');
StringHelper::sanitize('<script>alert(1)</script>');
```

### ArrayHelper

Array manipulation utilities:

```php
use App\Libraries\ArrayHelper;

ArrayHelper::get($array, 'key.nested', 'default');
ArrayHelper::set($array, 'key.nested', 'value');
ArrayHelper::only($array, ['key1', 'key2']);
ArrayHelper::except($array, ['key1']);
ArrayHelper::flatten($array);
ArrayHelper::merge($array1, $array2);
```
