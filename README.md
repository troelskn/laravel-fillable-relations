Laravel Fillable Relations
===

This library provides a trait for mixing in to an Eloquent Model. Doing so will enable support for fillable relations.

To use, first require in your composer file:

    {
        "require": {
            "troelskn/laravel-fillable-relations": "dev-master"
        },
        "repositories": [
            {
                "type": "vcs",
                "url": "git@github.com:troelskn/laravel-fillable-relations.git"
            }
        ]
    }

Then, in your code:

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

And you can now fill relations, like so:

    $foo = new Foo(
        [
            'cuux' => 42,
            'bar' => [
                'id' => 42
            ]
        ]
    );

Or perhaps:

    $foo = new Foo(
        [
            'cuux' => 42,
            'bar' => [
                'name' => "Ye Olde Pubbe"
            ]
        ]
    );

And also:

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