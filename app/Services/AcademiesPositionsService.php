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
        foreach ($academiesPositions as $academyAbv => $positions) {
            $academyId = Academy::where('abbreviation', '=', $academyAbv)->first()->id;
            $positions = array_map(fn ($position): array => ['name' => $position, 'created_at' => date('Y-m-d H:i:s')], $positions);
            Position::insert($positions);
            self::storeInitialAcademyPositions($positions, $academyId);
        }
    }

    /**
     * @param array $positions
     * @param int $academyId
     * 
     * @return void
     */
    private static function storeInitialAcademyPositions($positions, $academyId)
    {
        foreach ($positions as $position) {
            $positionId = Position::where('name', '=', $position['name'])->first()->id;
            $acPos = new AcademiesPositions();
            $acPos->position_id = $positionId;
            $acPos->academy_id = $academyId;
            $acPos->save();
        }
    }
}
