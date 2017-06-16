<?php

namespace LaravelFillableRelations\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Model;
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

    public function fillRelations(array $relations)
    {
        foreach ($relations as $relationName => $attributes) {
            $camelCaseName = camel_case($relationName);
            $relation = $this->{$camelCaseName}();

            if ($relation instanceof BelongsTo) {
                $this->fillBelongsToRelation($relation, $attributes);
            } elseif ($relation instanceof HasOne) {
                $this->fillHasOneRelation($relation, $attributes);
            } elseif ($relation instanceof HasMany) {
                $this->fillHasManyRelation($relation, $attributes);
            } elseif ($relation instanceof BelongsToMany) {
                $this->fillBelongsToManyRelation($relation, $attributes);
            } else {
                throw new RuntimeException("Unknown or unfillable relation type $relationName");
            }
        }
    }

    public function fill(array $attributes)
    {
        list($relations, $attributes) = $this->extractFillableRelations($attributes);

        parent::fill($attributes);

        $this->fillRelations($relations);

        return $this;
    }

    public static function create(array $attributes = [])
    {
        list($relations, $attributes) = (new static)->extractFillableRelations($attributes);

        $model = new static($attributes);
        $model->fillRelations($relations);
        $model->save();

        return $model;
    }

    /**
     * @param BelongsTo $relation
     * @param array $attributes
     */
    public function fillBelongsToRelation(BelongsTo $relation, array $attributes)
    {
        $klass = get_class($relation->getRelated());

        if ($attributes instanceof Model) {
            $entity = $attributes;
        } else {
            $entity = $klass::where($attributes)->firstOrFail();
        }
        $relation->associate($entity);
    }

    /**
     * @param HasOne $relation
     * @param array $attributes
     */
    public function fillHasOneRelation(HasOne $relation, array $attributes)
    {
        $klass = get_class($relation->getRelated());

        if ($attributes instanceof Model) {
            $entity = $attributes;
        } else {
            $entity = $klass::firstOrCreate($attributes);
        }

        $qualified_foreign_key = $relation->getForeignKey();
        list($table, $foreign_key) = explode('.', $qualified_foreign_key);
        $qualified_local_key_name = $relation->getQualifiedParentKeyName();
        list($table, $local_key) = explode('.', $qualified_local_key_name);
        $this->{$local_key} = $entity->{$foreign_key};
    }

    /**
     * @param HasMany $relation
     * @param array $attributes
     */
    public function fillHasManyRelation(HasMany $relation, array $attributes)
    {
        $klass = get_class($relation->getRelated());

        if (!$this->exists) {
            $this->save();
        }
        $relation->delete();
        foreach ($attributes as $row) {
            if ($row instanceof Model) {
                $entity = $row;
            } else {
                $entity = new $klass($row);
            }
            $relation->save($entity);
        }
    }

    /**
     * @param BelongsToMany $relation
     * @param array $attributes
     */
    public function fillBelongsToManyRelation(BelongsToMany $relation, array $attributes)
    {
        $klass = get_class($relation->getRelated());

        if (!$this->exists) {
            $this->save();
        }

        $relation->detach();
        foreach ($attributes as $row) {
            if ($row instanceof Model) {
                $entity = $row;
            } else {
                $entity = $klass::where($row)->firstOrFail();
            }
            $relation->attach($entity);
        }
    }
}
