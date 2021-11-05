<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Academy
 *
 * @property string $name
 * @property string|null $abbreviation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Academy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Academy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Academy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Academy whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Academy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Academy whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Academy whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Candidate[] $candidates
 * @property-read int|null $candidates_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Position[] $positions
 * @property-read int|null $positions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Academy whereId($value)
 */
class Academy extends Model
{
    protected $hidden = ['created_at','updated_at','pivot'];

    const ACADEMIES = [
        ['name' => 'Business to business', 'abbreviation' => 'B2B'],
        ['name' => 'Internet of things', 'abbreviation' => 'IoT']
    ];
    use HasFactory;

    public function positions()
    {
        return $this->belongsToMany(Position::class,'academies_positions');
    }
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
