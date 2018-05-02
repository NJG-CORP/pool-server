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
        Taxonomy::createTerm($gameTypeVocabulary->id, 'Любой');

        $gamePaymentTypeVocabulary = Taxonomy::createVocabulary("GamePaymentType");
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "Поровну");
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "Беру на себя");
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "За счет партнера");
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "Не имеет значения");

        $skillLevelVocabulary = Taxonomy::createVocabulary('SkillLevel');
        Taxonomy::createTerm($skillLevelVocabulary->id, 'Новичок');
        Taxonomy::createTerm($skillLevelVocabulary->id, 'Стандартный');
        Taxonomy::createTerm($skillLevelVocabulary->id, 'Профи');
    }
}
