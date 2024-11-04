<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use DB;

class SchoolController extends Controller
{

    public function index($doctype, Request $request)
    {
        // Ambil parameter dari query string
        $school = $request->input('school', 'Sekolah A'); // Default ke Sekolah A jika tidak ada
        $level = $request->input('level', 'SMA'); // Default ke SMA jika tidak ada
    
        // Membangun struktur dasar respons
        $response = [
            'doctype' => $doctype,
            'filters' => [
                'school' => [
                    'operator' => '=',
                    'value' => $school,
                ],
                'level' => [
                    'operator' => '=',
                    'value' => $level,
                ],
            ],
            'message' => [
                'code' => 200,
                'data' => [],
            ],
        ];
    
        // Mengambil data dari view v_schools sesuai dengan jenis dokumen
        switch ($doctype) {
            case 'students':
                $data = DB::table('v_schools')
                    ->select('student_name')
                    ->where('school_name', $school)
                    ->where('subject_level', $level)
                    ->get();
                break;
    
            case 'teachers':
                $data = DB::table('v_schools')
                    ->select('teacher_name')
                    ->where('school_name', $school)
                    ->get();
                break;
    
            case 'subjects':
                $data = DB::table('v_schools')
                    ->select('subject_title', 'subject_level')
                    ->where('school_name', $school)
                    ->where('subject_level', $level)
                    ->get();
                break;
    
            default:
                return response()->json(['error' => 'Invalid doctype'], 400);
        }
    
        $response['message']['data'] = $data->toArray(); // Mengkonversi ke array
    
        return response()->json($response);
    }
    

    public function store(Request $request, $doctype)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            // Tambahkan aturan validasi sesuai kebutuhan untuk model lainnya
            // Misalnya untuk teacher dan subject
            'subject_id' => 'nullable|exists:subjects,id', // Untuk teacher
            'code' => 'nullable|string|max:10' // Untuk subject
        ]);

        $response = [
            'doctype' => $doctype,
            'message' => [
                'code' => 201,
                'data' => null,
            ],
        ];

        switch ($doctype) {
            case 'teachers':
                $teacher = Teacher::create($validatedData);
                $response['message']['data'] = $teacher;
                break;

            case 'students':
                $student = Student::create($validatedData);
                $response['message']['data'] = $student;
                break;

            case 'subjects':
                $subject = Subject::create($validatedData);
                $response['message']['data'] = $subject;
                break;

            default:
                return response()->json(['error' => 'Invalid doctype'], 400);
        }

        return response()->json($response, 201);
    }

}
