<?php

namespace App\Exports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Size;

class CandidatesExport implements FromCollection,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $candidates = Candidate::all()->map(
            function ($candidate) {
                $candidate->education_institution= $candidate->education_institution()->get()->first()->name;
                $candidate->academy= $candidate->academy()->get()->first()->name;

                $positions = $candidate->positions;
                $candidate->positions=$this->aggregatePositions($positions);
                return $candidate;
            }
        );
        return $candidates;
    }
    public function aggregatePositions($positions):string
    {
        $aggregatedPositions="";
        for($i =0;$i<count($positions);$i++)
        {
            $position = $positions[$i]->name;
            $aggregatedPositions.=$position;
            if($i+1 != count($positions))
            {
                $aggregatedPositions.='; ';
            }
            
        }
        return $aggregatedPositions;
    }
    public function headings(): array
    {
        return [
            'id',
            'name',
            'surnname',
            'gender',
            'phone',
            'email',
            'application_date',
            'city',
            'status',
            'course',
            'CV',
            'education_institution',
            'academy',
            'positions',
            'comment',
        ];
    }
}
