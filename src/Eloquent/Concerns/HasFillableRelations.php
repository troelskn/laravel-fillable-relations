<?php

namespace LaravelFillableRelations\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

/**
 * Mix this in to your model class to enable fillable relations.
 * Usage:
 *     use Illuminate\Database\Eloquent\Model;
 *     use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;
 *
 *     class Foo extends Model
 *     {
 *         use HasFillableRelations;
 *         protected $fillable_relations = ['bar'];
 *
 *         function bar()
 *         {
 *             return $this->hasOne(Bar::class);
 *         }
 *     }
 *
 *     $foo = new Foo(['bar' => ['id' => 42]]);
 *     // or perhaps:
 *     $foo = new Foo(['bar' => ['name' => "Ye Olde Pubbe"]]);
 *
 */
trait HasFillableRelations
{
    /**
     * The relations that should be mass assignable.
     *
     * @var array
     */
    // protected $fillable_relations = [];

    public function fillableRelations()
    {
        return isset($this->fillable_relations) ? $this->fillable_relations : [];
    }

    public function extractFillableRelations(array $attributes)
    {
        $fillableRelationsData = [];
        foreach ($this->fillableRelations() as $relationName) {
            $val = array_pull($attributes, $relationName);
            if ($val) {
                $fillableRelationsData[$relationName] = $val;
            }
        }
        return [$fillableRelationsData, $attributes];
    }

    public function fillRelations(array $fillableRelationsData)
    {
        foreach ($fillableRelationsData as $relationName => $fillableData) {
            $camelCaseName = camel_case($relationName);
            $relation = $this->{$camelCaseName}();
            $klass = get_class($relation->getRelated());
            if ($relation instanceof BelongsTo) {
                $entity = $klass::where($fillableData)->firstOrFail();
                $relation->associate($entity);
            } elseif ($relation instanceof HasOne) {
                $entity = $klass::firstOrCreate($fillableData);
                $qualified_foreign_key = $relation->getForeignKey();
                list($table, $foreign_key) = explode('.', $qualified_foreign_key);
                $qualified_local_key_name = $relation->getQualifiedParentKeyName();
                list($table, $local_key) = explode('.', $qualified_local_key_name);
                $this->{$local_key} = $entity->{$foreign_key};
            } elseif ($relation instanceof HasMany) {
                if (!$this->exists) {
                    $this->save();
                }
                $relation->delete();
                foreach ($fillableData as $row) {
                    $entity = new $klass($row);
                    $relation->save($entity);
                }
            } elseif ($relation instanceof BelongsToMany) {
                if (!$this->exists) {
                    $this->save();
                }
                $relation->detach();
                foreach ($fillableData as $row) {
                    $entity = $klass::where($row)->firstOrFail();
                    $relation->attach($entity);
                }
            } else {
                throw new RuntimeException("Unknown or unfillable relation type $relationName");
            }
        }
    }

    public function fill(array $attributes)
    {
        list($fillableRelationsData, $attributes) = $this->extractFillableRelations($attributes);
        parent::fill($attributes);
        $this->fillRelations($fillableRelationsData);
        return $this;
    }

    public static function create(array $attributes = [])
    {
        list($fillableRelationsData, $attributes) = (new static)->extractFillableRelations($attributes);
        $model = new static($attributes);
        $model->fillRelations($fillableRelationsData);
        $model->save();
        return $model;
    }
}
