<?php

use Faker\Factory as Faker;
use App\Models\Dashboard\Queue;
use App\Repositories\Dashboard\QueueRepository;

trait MakeQueueTrait
{
    /**
     * Create fake instance of Queue and save it in database
     *
     * @param array $queueFields
     * @return Queue
     */
    public function makeQueue($queueFields = [])
    {
        /** @var QueueRepository $queueRepo */
        $queueRepo = App::make(QueueRepository::class);
        $theme = $this->fakeQueueData($queueFields);
        return $queueRepo->create($theme);
    }

    /**
     * Get fake instance of Queue
     *
     * @param array $queueFields
     * @return Queue
     */
    public function fakeQueue($queueFields = [])
    {
        return new Queue($this->fakeQueueData($queueFields));
    }

    /**
     * Get fake data of Queue
     *
     * @param array $postFields
     * @return array
     */
    public function fakeQueueData($queueFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'company_id' => $fake->randomDigitNotNull,
            'name' => $fake->word,
            'phone' => $fake->word,
            'third_party_code' => $fake->word,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $queueFields);
    }
}
