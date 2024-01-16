# A set of useful Laravel validation rules for shipping container numbers

This repository contains useful Laravel validation rules for shipping container numbers, based on:
https://github.com/stormpat/Container-validator/blob/master/src/Validator/Validator.php

## Installation

You can install the package via composer:

```bash
composer require softlogic-gt/laravel-validation-rules-container
```

The package will automatically register itself.

### Translations

If you wish to edit the package translations, you can run the following command to publish them into your `resources/lang` folder

```bash
php artisan vendor:publish --provider="SoftlogicGT\ValidationRulesContainer\ValidationRulesContainerServiceProvider"
```

## Available rules

-   [`Container`](#container)

### `Container`

Determine if the field under validation is a valid shipping container.

```php

use SoftlogicGT\ValidationRulesContainer\Rules\Container;

public function rules()
{
    $rules = [
        'container' => ['required', new Container()],
    ];
    $request->validate($rules)
}
```
