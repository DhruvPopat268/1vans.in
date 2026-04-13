<?php

namespace App\Exports;

use App\Models\EngineerAttendances;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EngineerAttendanceExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $month;
    protected $year;
    protected $engineer_id;

    public function __construct($month = null, $year = null, $engineer_id = null)
    {
        $this->month = $month;
        $this->year = $year;
        $this->engineer_id = $engineer_id;
    }

    public function collection()
    {
        $user = Auth::user();
        $projectId = $user->project_assign_id;

        $query = EngineerAttendances::with('project')
            ->where('project_id', $projectId);

        if ($this->engineer_id) {
            $query->where('engineer_id', $this->engineer_id);
        }

        if ($this->month && $this->year) {
            $query->whereMonth('date', $this->month)
                  ->whereYear('date', $this->year);
        }

        return $query->get();
    }

    public function map($attendance): array
    {
        
         // Convert attendance_type to readable text
    $attendanceType = '-';
    if ($attendance->attendance_type === 'P') {
        $attendanceType = 'Present';
    } elseif ($attendance->attendance_type === 'A') {
        $attendanceType = 'Absent';
    } elseif ($attendance->attendance_type === 'H') {
        $attendanceType = 'Holiday';
    }
        return [
            $attendance->date ?? '-',
            $attendance->project->project_name ?? '-',
            $attendance->check_in ? Carbon::parse($attendance->check_in)->format('h:i A') : '-',
            $attendance->check_out ? Carbon::parse($attendance->check_out)->format('h:i A') : '-',
            $attendance->duration ?? '-',
           $attendanceType,
            $attendance->late ?? '-',
            $attendance->overtime ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Project Name',
            'Check In',
            'Check Out',
            'Duration',
            'Attendance Type',
            'Late',
            'Overtime',
        ];
    }

    // ✅ Add row coloring based on attendance type & overtime
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestDataRow();

                for ($row = 2; $row <= $highestRow; $row++) { // start from row 2 (skip headings)
                    $attendanceType = $sheet->getCell("F$row")->getValue();
                    $overtime = $sheet->getCell("H$row")->getValue();

                    // Parse overtime
                    preg_match('/(\d+)h\s*(\d+)m/', $overtime, $matches);
                    $hours = isset($matches[1]) ? (int) $matches[1] : 0;
                    $minutes = isset($matches[2]) ? (int) $matches[2] : 0;
                    $totalMinutes = ($hours * 60) + $minutes;

                    $color = null;

                    if ($totalMinutes > 600) {
                        $color = 'FFf8d7da'; // Red
                    } elseif ($attendanceType === 'Holiday') {
                        $color = 'FFd4edda'; // Green
                    } elseif ($attendanceType === 'Absent') {
                        $color = 'FFffe5b4'; // Orange
                    }

                    if ($color) {
                        $sheet->getStyle("A$row:H$row")->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB($color);
                    }
                }
            },
        ];
    }
}