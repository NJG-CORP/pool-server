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
        Taxonomy::createTerm($gameTypeVocabulary->id, "Русский", 'russian');
        Taxonomy::createTerm($gameTypeVocabulary->id, "Снукер", 'snooker');
        Taxonomy::createTerm($gameTypeVocabulary->id, "Пул", 'pool');
        Taxonomy::createTerm($gameTypeVocabulary->id, 'Любой', 'all');

        $gamePaymentTypeVocabulary = Taxonomy::createVocabulary("GamePaymentType");
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "Поровну", 'half');
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "Беру на себя", 'me');
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "За счет партнера", 'you');
        Taxonomy::createTerm($gamePaymentTypeVocabulary->id, "Не имеет значения", 'unimportant');

        $skillLevelVocabulary = Taxonomy::createVocabulary('SkillLevel');
        Taxonomy::createTerm($skillLevelVocabulary->id, 'Новичок', 'beginner');
        Taxonomy::createTerm($skillLevelVocabulary->id, 'Стандартный', 'standard');
        Taxonomy::createTerm($skillLevelVocabulary->id, 'Профи', 'professional');
    }
}
