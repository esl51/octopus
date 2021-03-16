<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Roquie\LaravelPerPageResolver\Builder;

class EloquentBuilder extends Builder
{
    /**
     * @inheritdoc
     */
    protected function eagerLoadRelation(array $models, $name, Closure $constraints)
    {
        try {
            return parent::eagerLoadRelation($models, $name, $constraints);
        } catch (RelationNotFoundException $e) {
            // The eager loading request is not a relation - is it a pivot?
            if (head($models)->{$name} instanceof Pivot) {
                $relations = array_filter(array_keys($this->eagerLoad), function ($relation) use ($name) {
                    return $relation != $name && starts_with($relation, $name);
                });

                $pivots = $this->getModel()->newCollection(
                    array_pluck($models, $name)
                );

                $pivots->load(array_map(function ($relation) use ($name) {
                    return substr($relation, strlen($name) + 1);
                }, $relations));

                return $models;
            }

            throw $e;
        }
    }
}
