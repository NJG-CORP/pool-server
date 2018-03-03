<?php

namespace App\Http\Controllers;

use App\Services\TaxonomyService;
use Illuminate\Http\Request;

class TaxonomyController extends Controller
{
    /**
     * @var TaxonomyService $taxonomies
     */
    private $taxonomies;

    public function __construct(
        Request $request,
        TaxonomyService $taxonomies
    )
    {
        parent::__construct($request);
        $this->taxonomies = $taxonomies;
    }

    public function getVocabularies(Request $request){
        $vocabularies = $this->taxonomies->getVocabularies();
        return $this->responder->successResponse([
            'vocabularies' => $vocabularies
        ]);
    }

    public function getTerms(Request $request, $vocabularyId){
        $terms = $this->taxonomies->getVocabularyTerms($vocabularyId);
        return $this->responder->successResponse([
           'terms' => $terms
        ]);
    }
}
