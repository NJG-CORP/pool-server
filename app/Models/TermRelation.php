<?php

namespace App\Models;

use Devfactory\Taxonomy\Models\Term;
 use Illuminate\Database\Eloquent\Model;
use Devfactory\Taxonomy\TaxonomyTrait;

 
 class TermRelation extends Model
 {
     protected $table = 'term_relations';
     protected $fillable = [
         'relationable_id', 'relationable_type', 'term_id', 'vocabulary_id'
     ];

     public function term() {
             return $this->hasMany(Term::class, 'term_id');
    }
 }