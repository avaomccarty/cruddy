# Cruddy
Simple CRUD creation package for Laravel.

## Steps to Install
1) composer install ottiem/cruddy
2) php artisan vendor:publish
3) adjust any settings in the newly created config/cruddy.php file
4) add the cruddy connection to all migrations that create the table you want to turn into a basic CRUD resource: `Schema::connection('cruddy')`

## Vendor Package files:
When you run `php artisan vedor:publish` it will create a config file for you in config/cruddy.php. This is where you can set all your CRUD settings and options. For example, the `needs_ui` variable within config/cruddy.php controls whether to create the frontend view files or not. If you are making an API that does not require a frontend, then you should set this value to false so that the view files will not be created.
```
  // Within config/cruddy.php, we can control the creation of the frontend view files like so:
 'needs_ui' => false,
```


### Example:
```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFooTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('cruddy')->create('foo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foo');
    }
}
```

If these files do not already exist, then they will be created from running the example above. Files will not be overwritten if they already exist.
1) app/Http/Controllers/FooController.php
2) app/Models/Foo.php
3) app/Http/Requests/StoreFoo.php
4) app/Http/Requests/UpdateFoo.php
5) resources/views/foo/create.blade.php
6) resources/views/foo/edit.blade.php
7) resources/views/foo/index.blade.php
8) resources/views/foo/show.blade.php

This will be added to your routes/web.php file for the example above:
```
// Foo Resource
Route::resource('foos', 'App\Http\Controllers\FooController');
```
