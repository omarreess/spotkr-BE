<?php

namespace Modules\Country\Entities;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['name'];
}
