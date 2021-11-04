<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CandidateComment
 *
 * @property int $id
 * @property string $comment
 * @property int $candidate_id
 * @property string|null $date
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateComment whereCandidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateComment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateComment whereId($value)
 * @mixin \Eloquent
 */
class CandidateComment extends Model
{
    use HasFactory;
}
