## Installation

Simply clone this repository into the extensions/ subdirectory of your mediawiki installation,
with the directory name TripsitExtension (or change it if you like), then add the following to your LocalSettings.php:

```php
require_once "$IP/extensions/TripsitExtension/TripsitExtension.php";
```

## Usage

Include, for example, the following in your Wiki page source:

```
{{#tdose: ketamine}}
```
