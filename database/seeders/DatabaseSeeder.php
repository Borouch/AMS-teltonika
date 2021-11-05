<?php

namespace Database\Seeders;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Database\Seeder;
use App\Models\AcademiesPositions;
use App\Models\CandidatesPositions;
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
            $academyId = Academy::where('abbreviation', '=', $academyAbv)->first()->id;
            $positions = array_map(fn ($position): array => ['name' => $position,'created_at'=>date('Y-m-d H:i:s')], $positions);
            Position::insert($positions);
            $this->storeAcademyPositions($positions,$academyId);
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
    private function storeAcademyPositions($positions,$academyId)
    {
        foreach ($positions as $position)
        {
            $positionId = Position::where('name','=', $position['name'])->first()->id;
            $acPos = new AcademiesPositions();
            $acPos -> position_id = $positionId;
            $acPos -> academy_id = $academyId;
            $acPos->save();
        }
    }
}
