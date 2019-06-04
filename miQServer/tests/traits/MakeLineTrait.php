<?php

use Faker\Factory as Faker;
use App\Models\Dashboard\Line;
use App\Repositories\Dashboard\LineRepository;

trait MakeLineTrait
{
    /**
     * Create fake instance of Line and save it in database
     *
     * @param array $lineFields
     * @return Line
     */
    public function makeLine($lineFields = [])
    {
        /** @var LineRepository $lineRepo */
        $lineRepo = App::make(LineRepository::class);
        $theme = $this->fakeLineData($lineFields);
        return $lineRepo->create($theme);
    }

    /**
     * Get fake instance of Line
     *
     * @param array $lineFields
     * @return Line
     */
    public function fakeLine($lineFields = [])
    {
        return new Line($this->fakeLineData($lineFields));
    }

    /**
     * Get fake data of Line
     *
     * @param array $postFields
     * @return array
     */
    public function fakeLineData($lineFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'color' => $fake->word,
            'priority' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $lineFields);
    }
}
