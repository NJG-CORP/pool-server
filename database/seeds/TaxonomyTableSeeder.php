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

        $gameTypeVocabulary = Taxonomy::createVocabulary("GamePaymentType");
        Taxonomy::createTerm($gameTypeVocabulary->id, "Поровну");
        Taxonomy::createTerm($gameTypeVocabulary->id, "Беру на себя");

        $paymentTypeVocabulary = Taxonomy::createVocabulary('SkillLevel');
        Taxonomy::createTerm($paymentTypeVocabulary->id, 'Уровень игры1');
        Taxonomy::createTerm($paymentTypeVocabulary->id, 'Уровень игры2');
    }
}
