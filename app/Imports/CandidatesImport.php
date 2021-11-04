<?php

namespace App\Imports;

use DateTime;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use App\Utilities\ValidationUtilities;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CandidatesImport implements WithHeadingRow
{

    public static function validateCandidates(Collection $candidates)
    {
        $candidates = $candidates->map(function ($row) {
            CandidatesImport::prepareForValidation($row);
            CandidatesImport::validationFields($row);
            return [
                'name' => $row['name'],
                'surnname' => $row['surnname'],
                'gender' => $row['gender'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'application_date' => $row['application_date'],
                'education_institution' => $row['education_institution'],
                'course' => $row['course'],
                'city' => $row['city'],
                'status' => $row['status'],
                'positions' => $row['positions'],
                'can_manage_data' => $row['can_manage_data'],
                'comment' => $row['comment'],
                'academy' => $row['academy'],
                'CV' => $row['cv'],
            ];
        });
        return $candidates;
    }
    private static function validationFields($row)
    {

        $academy = $row['academy'];
        Validator::make(
            $row->toArray(),
            ValidationUtilities::CandidateStoreValidationRules($academy),
            ValidationUtilities::customMessages()
        )->validate();
    }
    private static function prepareForValidation(&$row)
    {
        $row['positions'] = array_map(fn ($position) => rtrim($position), explode('; ', $row['positions']));
        $row['application_date'] = date("Y-m-d", strtotime($row['application_date']));
        $row['can_manage_data'] = strtolower($row['can_manage_data']);
        if ($row['can_manage_data'] != null) {

            $row['can_manage_data'] = strtolower($row['can_manage_data']);
            $row['can_manage_data'] = filter_var($row['can_manage_data'], FILTER_VALIDATE_BOOLEAN);
        }
        return $row;
    }
}
