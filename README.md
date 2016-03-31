# PHP Represent

> Find the elected officials and electoral districts for any Canadian address or postal code, at all levels of government

A PHP library for [http://represent.opennorth.ca/](http://represent.opennorth.ca/)

## Install
Install using composer:
```
"require": {
  "seanmcn/php-represent": "1.*"
}
```

Alternatively you could just download the package and load it in yourself.

## Example Usage
```
$represent = new PHPRepresent\API();
$represent->getAll('boundaries', ['sets' => ['toronto-wards', 'ottawa-wards']]);
```

## API Documentation
- [Represent Civic Information API Documentation](http://represent.opennorth.ca/api/)

## Library Documentation

### get($path, $params, $throttle);

Returns a single result from API path and parameters provided.

**Parameters**:
- `$path` - The API Path of your request.
- `$params` - $_GET variables you want to append to the request. Allows supplying multiple values per key by array or comma seperated.
- `$throttle` - Default TRUE. Option to ignore the API rate limit.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$path = 'boundaries';
$params = ['sets' => ['toronto-wards', 'ottawa-wards']];
$represent->get($path, $params);
```

### getAll($path, $params);

Returns all results from API path and parameters provided.

**Parameters**:
- `$path` - The API Path of your request.
- `$params` - $_GET variables you want to append to the request. Allows supplying multiple values per key by array or comma seperated.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$path = 'boundaries';
$params = ['sets' => 'toronto-wards,ottawa-wards'];
$represent->getAll($path, $params);
```

###  postcode($postcode);
Find representatives and boundaries by postal code.

**Parameters**:
- `$postcode` - Post code to find representatives and boundaries for.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->postcode('L5G4L3');
```
###  boundarySets($name, $params);
Return boundary sets which are groups of electoral districts, like BC provincial districts or Toronto wards.

**Parameters**:
- `$name` - Optional, if provided will return the singular boundary set.
- `$params` - $_GET variables you want to append to the request. Allows supplying multiple values per key by array or comma seperated.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->boundarySets();
```

###   boundaries($boundarySet, $name, $representatives, $params)
Return boundaries of electoral districts, can be proved a set like `toronto-wards` for boundaries of a singular set. 

>All Parameters are optional however `$name` requires you provide `$boundarySet` and `$representatives` requires you provided both `$boundarySet` and `$name`

**Parameters**:
- `$boundarySet` - Optional, if provided returns boundaries from a singular boundary set
- `$name` - Optional, if provided will return a singular boundary
- `$representatives` - Optional, if provided will return representatives for the boundary. 
- `$params` - Optional, $_GET variables you want to append to the request. Allows supplying multiple values per key by array or comma seperated.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
// One Set
$represent->boundaries('toronto-wards');
// Multiple Sets
$represent->boundaries(null, null, false,  ['sets' => ['toronto-wards', 'ottawa-wards']]);

```

###  representativeSets($set);
Returns all or a singular representative set. 

>A representative set is a group of elected officials, like the House of Commons or Toronto City Council.

**Parameters**:
- `$set` - Optional, if provided will return the singular represenative set.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->representativeSets('north-dumfries-township-council');
```

###  representatives($set, $params);
Returns a list of representatives.

**Parameters**:
- `$set` - Optional, if provided will return the represenatives for a singular set.
- `$params` - Optional, `$_GET` variables you want to append to the request. Allows supplying multiple values per key by array or comma seperated.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->representativeSets('north-dumfries-township-council');
```
###  elections($election);
Returns a list of elections or a singular election.

>**This doesn't have any data right now so I am usnure if this is working correctly.**

**Parameters**:
- `$election` - Optional, if provided will return the a singular election based on provided ID.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->elections();
```

###  candidates($election, $params);
Returns a list of all candidates for all elections or all candidates for a singular election

>**This doesn't have any data right now so I am unsure if this is working correctly.**

**Parameters**:
- `$election` - Optional, if provided will return the candidates for a singular election.
- `$params` - Optional, `$_GET` variables you want to append to the request. Allows supplying multiple values per key by array or comma seperated.

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->candidates();
```

###  setInsecure();
Option to disable using HTTPS, if for example you are having certificate problems while testing this out.
> Note: You really shouldn't use this in Production

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->setInsecure();
$represent->boundaries('toronto-wards');
```

###  setRateLimit($limit);
Option to set the rate limit (per minute) to whatever you choose. The default rate limit is 60 requests per minute.

> Note: Don't change this unless you have agreed upon a higher rate limit with [Open North](mailto:represent@opennorth.ca) otherwise you're going to get HTTP 503 errors

**Example Usage:**
```php
$represent = new PHPRepresent\API();
$represent->setRateLimit(42);
$represent->boundaries('ottawa-wards');
```