<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CourseBundleCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = json_decode($value, true);

        if(! $value)
        {
            return null;
        }

        for($i = 0; $i<count($value); $i++){
            $value[$i]['price'] = (float) $value[$i]['price'];
            $value[$i]['discount'] = isset($value[$i]['discount']) ? (float) $value[$i]['discount'] : null;
            $value[$i]['sessions_count'] = (int) $value[$i]['sessions_count'];
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return json_encode($value);
    }
}
