<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\CandidatesPositions;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatesPositionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CandidatesPositions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $candidates = Candidate::all();
        $positions = Position::all();
        return [
            'position_id'=>$this->faker->randomElement($positions)->id,
            'candidate_id'=>$this->faker->randomElement($candidates)->id,
        ];
    }
}
