<?php

namespace Database\Factories;

use App\Models\Academy;
use App\Models\Candidate;
use App\Models\EducationInstitution;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $genders = Candidate::GENDERS;
        $courses = Candidate::COURSES;
        $institutions = EducationInstitution::all();
        $academies = Academy::all();
        return [
            'name' => $this->faker->name(),
            'surnname' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement($genders),
            'phone' => $this->faker->numerify('86#######'),
            'email' => $this->faker->email(),
            'application_date' => $this->faker->dateTimeBetween('-1 month'),
            'education_institution' => $this->faker->randomElement($institutions)->name,
            'city' => $this->faker->city(),
            'course' => $this->faker->randomElement($courses),
            'academy' => $this->faker->randomElement($academies)->name,
            //
        ];
    }
}
