Laravel Fillable Relations
===

This library provides a trait for mixing in to an Eloquent Model. Doing so will enable support for fillable relations.

To use, first require in your composer file:

```
composer require troelskn/laravel-fillable-relations
```

Then, in your code:

```php
<?php
namespace MyApp\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Foo extends Model
{
    use HasFillableRelations;
    protected $fillable_relations = ['bar'];

    function bar()
    {
        return $this->hasOne(Bar::class);
    }
}

class Bar extends Model
{
    use HasFillableRelations;
    protected $fillable_relations = ['foos'];

    function foos()
    {
        return $this->hasMany(Foo::class);
    }
}
```

And you can now fill relations, like so:

```php
$foo = new Foo(
    [
        'cuux' => 42,
        'bar' => [
            'id' => 42
        ]
    ]
);
```

Or perhaps:

```php
$foo = new Foo(
    [
        'cuux' => 42,
        'bar' => [
            'name' => "Ye Olde Pubbe"
        ]
    ]
);
```

And also:

```php
$bar = new Bar(
    [
        'name' => "Ye Olde Pubbe",
        'foos' => [
            [
                'cuux' => 42
            ],
            [
                'cuux' => 1337
            ]
        ]
    ]
);
```

In order to automatically detach empty relations, pass an empty array:

```php
$bar->fill([
    'foos' => [] // Detach all foos
]);

$bar->save();
```

You can use Laravel [validator array rule](https://laravel.com/docs/5.8/validation#rule-array)
 to preserve empty arrays passed to the request:

```php
class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'foos' => [
                'array',
            ],
        ];
    }
}
```

And then update attributes and relations in one line in the controller:

```php
public function update(UpdateRequest $request, Bar $bar)
{
    $bar->fill($request->validated())->save();
}
```