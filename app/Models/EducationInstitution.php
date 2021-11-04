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
 */
class EducationInstitution extends Model
{
    const EDUCATION_INSTITUTIONS =
    [
        'Kaunas University of Technology',
        'Vilnius Gediminas technical university'
    ];
    use HasFactory;
}
