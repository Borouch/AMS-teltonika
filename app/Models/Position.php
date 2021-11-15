<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Position
 *
 * @property int $id
 * @property string $name
 * @property string $academy
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereAcademy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Position extends Model
{
    protected $with = [];
    protected $hidden = ['updated_at','pivot'];
    const ACADEMIES_POSITIONS = [
        'B2B' =>
        [
            'Negotiation skills',
            'Sales techniques',
            'objections overcoming skills',
            'presentations: tool for selling ideas',
            'business communication skills'
        ],
        'IoT' => [
            'IoT devices testing',
            'embedded programming',
            'web programming',
            'technical support',
            'cad design engineering',
            'electronics engineering'
        ],
    ];
    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'candidates_positions');
    }
    public function academies()
    {
        return $this->belongsTomany(Academy::class,'academies_positions');
    }
    use HasFactory;
}
