<?php

namespace Database\Seeders;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\CandidatesPositions;
use Illuminate\Database\Seeder;
use App\Models\EducationInstitution;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $academies = Academy::ACADEMIES;
        Academy::insert($academies);
        $academiesPositions = Position::ACADEMIES_POSITIONS;
        foreach ($academiesPositions as $academyAbv => $positions) {
            $academyName = Academy::where('abbreviation', '=', $academyAbv)->first()->name;
            $positions = array_map(fn ($position): array => ['name' => $position, 'academy' => $academyName], $positions);
            Position::insert($positions);
        }
        $institutions = EducationInstitution::EDUCATION_INSTITUTIONS;
        $institutions = array_map(
            fn ($institution): array =>
            ['name' => $institution],
            $institutions
        );
        EducationInstitution::insert($institutions);
        Candidate::factory(5)->create();
        CandidatesPositions::factory(10)->create();
    }
}
