<?php

namespace App\Imports;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Models\EducationInstitution;
use App\Utilities\ValidationUtilities;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class CandidatesImport implements WithHeadingRow, WithCustomCsvSettings
{
    public function getCsvSettings(): array
    {
      # Define your custom import settings for only this class
        return [
          'input_encoding' => 'UTF-8',
          'delimiter' => ","
        ];
    }
    /**
     * @param Collection $candidates
     *
     * @return array
     */
    public static function processCandidates(Collection $candidates)
    {
        $candidates = $candidates->map(function ($row) {

            // var_dump(json_encode($row));
            $row = $row->toArray();
             CandidatesImport::validationFields($row);
            $unixtime = strtotime($row['application_date']);
            $row['application_date'] = date('Y-m-d', $unixtime);
            $candPosNames = self::getCandidatePOsitionsIds($row);
            $comments = explode('; ', $row['comments']);
            $eduId = EducationInstitution::where('name','=',$row['education_institution'])->first()->id;
            $acId = Academy::where('name','=',$row['academy'])->first()->id;
            return [
                'name' => $row['name'],
                'surnname' => $row['surnname'],
                'gender' => $row['gender'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'application_date' => $row['application_date'],
                'education_institution_id' => $eduId ,
                'course' => $row['course'],
                'city' => $row['city'],
                'status' => $row['status'],
                'positions' => $candPosNames,
                'can_manage_data' => $row['can_manage_data'],
                'comments' => $comments,
                'academy_id' => $acId,
                'CV' => $row['cv'],
            ];
        });
        return $candidates;
    }
    /**
     * @param array $row
     *
     * @return array
     */
    private static function getCandidatePOsitionsIds($row)
    {
        $academyName = $row['academy'];
        $academy = Academy::where('name', '=', $academyName)->first();
        $acPositions = $academy->positions()->get();
        $positionIds = $acPositions->map(fn ($p) => $p->id);
        $positionIds = $positionIds->filter(function ($id) use ($row) {
            $name  = Position::find($id)->name;
            $name = strtolower(str_replace(' ', '_', $name));
            $name = str_replace(':', '', $name);
            if ($row[$name] == '1') {
                return true;
            } else {
                false;
            }
        });
        return $positionIds->toArray();
    }
    /**
     * @param array $row
     *
     * @return void
     */
    private static function validationFields($row)
    {

        Validator::make(
            $row,
            self::candidateImportValidationRules($row)
        )->validate();
    }

    /**
     * @param array $row
     *
     * @return array
     */
    private static function candidateImportValidationRules($row)
    {

        $academies = Academy::all()->map(fn ($academy): string => $academy->name);

        Validator::make(
            $row,
            ['academy' => 'required|' . Rule::in($academies),]
        )->validate();
        $institutions = EducationInstitution::all();
        $institutions = $institutions->map(fn ($institution): string => $institution->name);
        $academyName = $row['academy'];
        $academy = Academy::where('name', '=', $academyName)->first();
        $academyPositions = $academy->positions()->get();
        $allPositions = Position::all()->map(fn ($p) => $p->name);
        $academyPositions = $academyPositions->map(fn ($position) => $position->name);
        $notInAcPositions = $allPositions->diff($academyPositions);
        $academyPositionsRules = $academyPositions->map(fn ($name) => [$name => 'Required|' . Rule::in(['0', '1'])]);
        $notInAcPositionsRules = $notInAcPositions->map(fn ($name) => [$name => 'Required|' . Rule::in(['0'])]);
        return [
            'name' => 'required|Letter_space|min:2',
            'surnname' => 'required|Letter_space|min:2',
            'city' => 'required|Letter_space|min:2',
            'comments' => 'nullable|text|max:1000',
            'gender' => 'required|' . Rule::in(Candidate::GENDERS),
            'email' => 'required|email',
            'application_date' => 'required|date',
            'education_institution' => 'required|' . Rule::in($institutions),
            'course' => 'required|' . Rule::in(Candidate::COURSES),
            'academy' => 'required|' . Rule::in($academies),
            'can_manage_data' => 'required|' . Rule::in(['0', '1']),
            ...$academyPositionsRules,
            ...$notInAcPositionsRules,
            'status' => 'nullable|' . Rule::in(Candidate::STATUSES),
            'phone' => 'nullable|phone',
            'CV' => 'nullable|max:10000|mimes:pdf',

        ];
    }
}
