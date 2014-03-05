## TableBuilder

A simple table builder implementation. Build tables from from data.
Accepted data is an array of:

* Arrays
* Objects
* Collections (Laravel Specific)

## Installation

Install the package through [Composer](http://getcomposer.org/). Edit your project's `composer.json` file by adding:

```php
"require": {
	"gloudemans/tablebuilder": "dev-master"
}
```

Next, run the Composer update command from the Terminal:

    composer update

### Laravel

If you want to use this package in your Laravel application all you have to do is add the service provider of the package and alias the package. To do this open your `app/config/app.php` file.

Add a new line to the `service providers` array:

	'Gloudemans\TableBuilder\TableBuilderServiceProvider'

And finally add a new line to the `aliases` array:

	'Table'            => 'Gloudemans\TableBuilder\Facades\Table',

Now you're ready to start using the table builder in your application.