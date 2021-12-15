<?php

namespace App\Services;

use App\Models\Academy;
use App\Models\Position;
use App\Models\AcademiesPositions;

class AcademiesPositionsService
{
    /**
     * @return void
     */
    public static function storeInitialAcademiesPositions()
    {
        $academiesPositions = Position::ACADEMIES_POSITIONS;
        foreach ($academiesPositions as $academyAbv => $positionsNames) {
            $academyId = Academy::where('abbreviation', '=', $academyAbv)->first()->id;
            $positionsNames = array_map(fn($posName) => ['name' => $posName] ,$positionsNames);
            Position::upsert($positionsNames, ['name']);
            self::storeInitialAcademyPositions($positionsNames, $academyId);
        }
    }

    /**
     * @param array $positionsNames
     * @param int $academyId
     *
     * @return void
     */
    private static function storeInitialAcademyPositions(array $positionsNames, int $academyId)
    {
        foreach ($positionsNames as $positionName) {
            $positionId = Position::where('name', '=', $positionName['name'])->first()->id;
            $acPos = new AcademiesPositions();
            $acPos->position_id = $positionId;
            $acPos->academy_id = $academyId;
            $acPos->save();
        }
    }
}
