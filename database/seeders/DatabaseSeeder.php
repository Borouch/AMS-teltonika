<?php

namespace Database\Seeders;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Database\Seeder;
use App\Models\AcademiesPositions;
use App\Models\CandidatesPositions;
use App\Models\EducationInstitution;
use App\Services\AcademiesPositionsService;
use App\Services\EducationInstitutionService;

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
        Academy::upsert($academies,['name','abbreviation']);
        AcademiesPositionsService::storeInitialAcademiesPositions();
        EducationInstitutionService::storeInitialEdu();
        RoleService::storeInitialRoles();
        Candidate::factory(10)->create();
        CandidatesPositions::factory(20)->create();
        UserService::storeInitialAdminUser();
    }

}
