<?php
namespace LaravelFillableRelations\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class Forum extends Model
{
    protected $table = 'poly_forums';
    use HasFillableRelations;
    public $timestamps = true;

    protected $fillable_relations = ['title'];

    public function title()
    {
        return $this->morphMany(TitleTranslation::class, 'translatable');
    }
}

class TitleTranslation extends Model
{
    protected $table = 'poly_title_translations';
    use HasFillableRelations;
    public $timestamps = true;

    protected $fillable = [
        'text',
        'translatable_id',
        'translatable_type',
    ];

    public function translatable()
    {
        return $this->morphTo();
    }
}
