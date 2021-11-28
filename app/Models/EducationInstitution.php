<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EducationInstitution
 *
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution query()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution whereName($value)
 * @mixin \Eloquent
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution whereId($value)
 * @property string|null $abbreviation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Candidate[] $candidates
 * @property-read int|null $candidates_count
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EducationInstitution whereUpdatedAt($value)
 */
class EducationInstitution extends Model
{
    use HasFactory;
    protected $hidden = ['updated_at'];
    public const EDUCATION_INSTITUTIONS =
    [
        'Kaunas University of Technology',
        'Vilnius Gediminas technical university'
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
