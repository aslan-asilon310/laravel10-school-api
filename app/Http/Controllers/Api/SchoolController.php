<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;

class SchoolController extends Controller
{
    public function index($doctype)
    {
        switch ($doctype) {
            case 'teachers':
                return Teacher::all();
            case 'students':
                return Student::all();
            case 'subjects':
                return Subject::all();
            default:
                return response()->json(['error' => 'Invalid doctype'], 400);
        }
    }

    public function store(Request $request, $doctype)
    {
        switch ($doctype) {
            case 'teachers':
                $teacher = Teacher::create($request->all());
                return response()->json($teacher, 201);
            case 'students':
                $student = Student::create($request->all());
                return response()->json($student, 201);
            case 'subjects':
                $subject = Subject::create($request->all());
                return response()->json($subject, 201);
            default:
                return response()->json(['error' => 'Invalid doctype'], 400);
        }
    }
}
