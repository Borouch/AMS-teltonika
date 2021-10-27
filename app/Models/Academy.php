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
 */
class Academy extends Model
{
    const ACADEMIES = [
        ['name' => 'Business to business', 'abbreviation' => 'b2b'],
        ['name' => 'Internet of things', 'abbreviation' => 'IoT']
    ];
    use HasFactory;
}
