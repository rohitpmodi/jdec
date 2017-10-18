<?php

use Faker\Factory as Faker;
use App\Models\Section;
use App\Repositories\SectionRepository;

trait MakeSectionTrait
{
    /**
     * Create fake instance of Section and save it in database
     *
     * @param array $sectionFields
     * @return Section
     */
    public function makeSection($sectionFields = [])
    {
        /** @var SectionRepository $sectionRepo */
        $sectionRepo = App::make(SectionRepository::class);
        $theme = $this->fakeSectionData($sectionFields);
        return $sectionRepo->create($theme);
    }

    /**
     * Get fake instance of Section
     *
     * @param array $sectionFields
     * @return Section
     */
    public function fakeSection($sectionFields = [])
    {
        return new Section($this->fakeSectionData($sectionFields));
    }

    /**
     * Get fake data of Section
     *
     * @param array $postFields
     * @return array
     */
    public function fakeSectionData($sectionFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'meta_description' => $fake->word,
            'slug' => $fake->word,
            'lang' => $fake->word
        ], $sectionFields);
    }
}
