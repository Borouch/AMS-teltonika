<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Candidate
 *
 * @property int $id
 * @property string $name
 * @property string $surnname
 * @property string $gender
 * @property string|null $phone
 * @property string $email
 * @property string $application_date
 * @property string $education_institution
 * @property string $city
 * @property string $status
 * @property string $course
 * @property string $academy
 * @property string $comment
 * @property string $CV
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CandidateFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereAcademy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereApplicationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereCV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereEducationInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereSurnname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Candidate extends Model
{
    protected $with = ['positions'];
    protected $hidden = ['created_at','updated_at'];
    const COURSES = [
        'first stage 1',
        'first stage 2',
        'first stage 3',
        'first stage 4',
        'second stage 1',
        'second stage 2',
        'bachelor',
        'masters',
        'not studying'
    ];

    const GENDERS =
    [
        'male',
        'female',
    ];
    const STATUSES =
    [
        'candidate',
        'called for interview',
        'interviewed',
        'accepted for intership',
        'recruited',
        'not accepted for intership',
        'not recruited',
        'declined',
        'next'
    ];

    public function positions()
    {
        return $this->belongsToMany(Position::class,'candidates_positions');
    }

    use HasFactory;
}
