<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Dashboard\Tag;
use App\Repositories\Dashboard\TagRepository;

trait MakeTagTrait
{
    /**
     * Create fake instance of Tag and save it in database
     *
     * @param array $tagFields
     * @return Tag
     */
    public function makeTag($tagFields = [])
    {
        /** @var TagRepository $tagRepo */
        $tagRepo = \App::make(TagRepository::class);
        $theme = $this->fakeTagData($tagFields);
        return $tagRepo->create($theme);
    }

    /**
     * Get fake instance of Tag
     *
     * @param array $tagFields
     * @return Tag
     */
    public function fakeTag($tagFields = [])
    {
        return new Tag($this->fakeTagData($tagFields));
    }

    /**
     * Get fake data of Tag
     *
     * @param array $tagFields
     * @return array
     */
    public function fakeTagData($tagFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $tagFields);
    }
}
