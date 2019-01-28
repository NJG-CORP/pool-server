<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermRelation extends Model
{
    protected $table = 'term_relations';
    protected $fillable = [
        'relationable_id', 'relationable_type', 'term_id', 'vocabulary_id'
    ];
}
