<?php

use Illuminate\Database\Seeder;

class TaxonomyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gameTypeVocabulary = Taxonomy::createVocabulary("GameType");
        Taxonomy::createTerm($gameTypeVocabulary->id, "Русский");
        Taxonomy::createTerm($gameTypeVocabulary->id, "Снукер");
        Taxonomy::createTerm($gameTypeVocabulary->id, "Пул");
    }
}
