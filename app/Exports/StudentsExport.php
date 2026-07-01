<?php
namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Student::with(['studentClass','section','session'])
            ->when($this->filters['class_id'] ?? null, fn($q) => $q->where('class_id', $this->filters['class_id']))
            ->when($this->filters['status'] ?? null, fn($q) => $q->where('status', $this->filters['status']))
            ->orderBy('class_id')->orderBy('roll_number')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student ID', 'Name', 'Name (EN)', 'Gender', 'Date of Birth',
            'Religion', 'Blood Group', 'Class', 'Section', 'Session',
            'Roll No', 'Father Name', 'Mother Name', 'Guardian Phone',
            'Address', 'Admission Date', 'Status'
        ];
    }

    public function map($student): array
    {
        return [
            $student->student_id,
            $student->name,
            $student->name_en ?? '',
            $student->gender,
            $student->date_of_birth->format('Y-m-d'),
            $student->religion,
            $student->blood_group ?? '',
            $student->studentClass->name ?? '',
            $student->section->name ?? '',
            $student->session->name ?? '',
            $student->roll_number ?? '',
            $student->father_name,
            $student->mother_name,
            $student->guardian_phone,
            $student->address,
            $student->admission_date->format('Y-m-d'),
            $student->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
