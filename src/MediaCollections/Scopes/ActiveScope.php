<?php

namespace Spatie\MediaLibrary\MediaCollections\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where($model->getActiveColumnName() ?? 'active', true);
    }
}
