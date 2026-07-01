<?php
namespace App\Http\Controllers;
use App\Models\{Student, Classes, Section, Session};
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class StudentImportController extends Controller
{
    public function index()
    {
        $classes  = Classes::orderBy('numeric_name')->get();
        $sessions = Session::orderByDesc('id')->get();
        $currentSession = Session::where('is_current',true)->first();
        return view('students.import', compact('classes','sessions','currentSession'));
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="student_import_sample.csv"',
        ];

        $rows = [
            ['নাম', 'ইংরেজি নাম', 'জন্ম তারিখ (Y-m-d)', 'লিঙ্গ (male/female)', 'ধর্ম', 'রক্তের গ্রুপ', 'ঠিকানা', 'পিতার নাম', 'মাতার নাম', 'অভিভাবকের ফোন', 'রোল নম্বর'],
            ['মোহাম্মদ সাকিব', 'Mohammad Sakib', '2010-05-15', 'male', 'Islam', 'A+', 'গ্রাম: নমুনা, জেলা: ঢাকা', 'মোঃ আব্দুল করিম', 'বেগম রহিমা', '01711000001', '1'],
            ['ফাতেমা বেগম', 'Fatema Begum', '2011-03-20', 'female', 'Islam', 'B+', 'গ্রাম: নমুনা, জেলা: চট্টগ্রাম', 'মোঃ রফিকুল ইসলাম', 'বেগম নাসরিন', '01711000002', '2'],
        ];

        $callback = function() use ($rows) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file'       => 'required|file|mimes:csv,xlsx,xls|max:5120',
            'class_id'   => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'session_id' => 'required|exists:academic_sessions,id',
        ]);

        $file      = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $data      = [];

        if ($extension === 'csv') {
            $handle = fopen($file->getPathname(), 'r');
            // Skip BOM
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") rewind($handle);
            $header = fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        } else {
            // Excel
            $collection = Excel::toCollection(new class implements ToCollection {
                public function collection(Collection $rows) {}
            }, $file);
            $rows = $collection->first();
            $rows->shift(); // skip header
            foreach ($rows as $row) {
                $data[] = $row->toArray();
            }
        }

        $success = 0;
        $errors  = [];

        foreach ($data as $i => $row) {
            try {
                if (empty($row[0])) continue;

                Student::create([
                    'name'           => $row[0] ?? '',
                    'name_en'        => $row[1] ?? null,
                    'date_of_birth'  => $row[2] ?? now()->subYears(12)->format('Y-m-d'),
                    'gender'         => in_array($row[3],['male','female']) ? $row[3] : 'male',
                    'religion'       => $row[4] ?? 'Islam',
                    'blood_group'    => $row[5] ?? null,
                    'address'        => $row[6] ?? 'N/A',
                    'father_name'    => $row[7] ?? 'N/A',
                    'mother_name'    => $row[8] ?? 'N/A',
                    'guardian_phone' => $row[9] ?? '01000000000',
                    'roll_number'    => !empty($row[10]) ? (int)$row[10] : null,
                    'class_id'       => $request->class_id,
                    'section_id'     => $request->section_id,
                    'session_id'     => $request->session_id,
                    'admission_date' => today(),
                    'status'         => 'active',
                ]);
                $success++;
            } catch (\Exception $e) {
                $errors[] = "সারি ".($i+2).": ".$e->getMessage();
            }
        }

        $message = "{$success} জন ছাত্র সফলভাবে import হয়েছে!";
        if (count($errors)) {
            $message .= " ".count($errors)." টি error হয়েছে।";
        }

        return back()->with('success', $message)->with('import_errors', $errors);
    }
}
