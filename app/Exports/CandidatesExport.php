<?php

namespace App\Exports;

use Exception;
use App\Models\Position;
use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CandidatesExport implements WithHeadings, FromArray
{
    public function array(): array
    {
        $candidates = Candidate::all()->toArray();
        $candidates = array_map(function ($candidate) {
            $positionsExport = Position::all()->mapWithKeys(fn ($pos) => [$pos->name => '0'])->toArray();
            $candPos = $candidate['positions'];
            foreach ($candPos  as $pos) {
                $positionsExport[$pos['name']] = '1';
            }
            $positionsExportValues = [];
            foreach ($positionsExport as $key => $value) {
                array_push($positionsExportValues, $value);
            }
            $comments = $candidate['comments'];
            $comments = array_map(fn ($c) => $c['content'], $comments);
            $aggComment = $this->aggregateComments($comments);
            return [
                $candidate['id'],
                $candidate['name'],
                $candidate['surnname'],
                $candidate['gender'],
                $candidate['phone'],
                $candidate['email'],
                $candidate['application_date'],
                $candidate['city'],
                $candidate['status'],
                $candidate['course'],
                $candidate['education_institution']['name'],
                $candidate['academy']['name'],
                $candidate['CV'],
                $candidate['can_manage_data'],
                $aggComment,
                ...$positionsExportValues
            ];
        }, $candidates);
        return $candidates;
    }

    public function aggregateComments($comments): string
    {
        $aggregatedContent = "";
        for ($i = 0; $i < count($comments); $i++) {
            $content = $comments[$i];
            $aggregatedContent .= $content;
            if ($i + 1 != count($comments)) {
                $aggregatedContent .= '; ';
            }
        }
        return $aggregatedContent;
    }
    public function headings(): array
    {
        $pNames = Position::all()->map(fn ($p) => $p->name)->toArray();
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
            'education_institution',
            'academy',
            'CV',
            'can_manage_data',
            'comment',
            ...$pNames,
        ];
    }
}
