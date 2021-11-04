<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AcademiesPositions
 *
 * @property int $id
 * @property int $position_id
 * @property int $academy_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions query()
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions whereAcademyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademiesPositions whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AcademiesPositions extends Model
{
    use HasFactory;
}
