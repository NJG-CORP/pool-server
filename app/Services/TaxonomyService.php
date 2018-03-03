<?php
namespace App\Services;

use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\Vocabulary;

class TaxonomyService
{
    public function getVocabularies(){
        return Vocabulary::all();
    }

    public function getVocabularyTerms($vocabularyId){
        return Term::where([
            'vocabulary_id' => $vocabularyId
        ])->get();
    }
}
