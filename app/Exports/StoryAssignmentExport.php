<?php

namespace App\Exports;

use App\Models\Story;
use App\Models\StoryAssignment;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class StoryAssignmentExport implements WithMapping, Responsable, WithHeadings, FromCollection, WithEvents, ShouldAutoSize
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    public $length;
    public $request;
    public $stories;

    public function __construct(Request $request)
    {

        $this->request = $request;
        $this->length = 1;
        $this->stories = Story::get();
    }

    public function headings(): array
    {

        $headers = [
            'School',
            'Teacher',
            'Students Grade',
            'Story Grade (Level)',
            'Stories',
            'Completed Students Assignments',
            'Uncompleted Students Assignments',
        ];
       return $headers;
        // Wrap each header in r() for translation
//        return array_map(function($header) {
//            return r($header);
//        }, $headers);
    }

    public function map($row): array
    {

       return [
          $row->teacher->school->name,
          $row->teacher->name,
           storyGradesSys()[$row->students_grade],
           storyGradesSys()[$row->story_grade],
          $this->stories->whereIn('id',$row->stories_ids)->pluck('name')->implode(','),
          $row->completed_count?:' 0',
          $row->uncompleted_count?:' 0',
       ];
    }

    public function collection()
    {


       return StoryAssignment::with(['teacher.school'])
           ->withCount([
               'userStoryAssignments as completed_count' => function ($query) {
                   $query->where('completed', true);
               }, 'userStoryAssignments as uncompleted_count' => function ($query) {
                   $query->where('completed', false);
               },
           ])->filter($this->request)->get();



    }

    public function drawings()
    {
        return new Drawing();
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet;

        // Assuming the headers are in the first row
        $headerRange = 'A1:' . $sheet->getDelegate()->getHighestColumn() . '1';

        // Assuming the data starts from the first row and column
        $dataRange = 'A1:' . $sheet->getDelegate()->getHighestColumn() . $sheet->getDelegate()->getHighestRow();

        $sheet->getDelegate()->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        // Apply styling or any other adjustments to the cell range
        $sheet->getDelegate()->getStyle($dataRange)->applyFromArray([
            'font' => [
                'size' => 12,
            ],
//            'borders' => [
//                'allBorders' => [
//                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                ],
//            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],

        ]);
        if (app()->getLocale() == 'ar'){
            $sheet->setRightToLeft(true);
        }
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet'],
        ];
    }
}
