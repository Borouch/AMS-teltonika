<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CandidatesPositions
 *
 * @property int $id
 * @property int $position_id
 * @property int $candidate_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CandidatesPositionsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions query()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions whereCandidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidatesPositions whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CandidatesPositions extends Model
{
    use HasFactory;
}
