<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Employee;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index()
    {
        try {
            $employees = Employee::all();

            return ResponseFormatter::success($employees, 'Ini adalah data employee');
        } catch (\Throwable $th) {
            return ResponseFormatter::error(null, 'Maaf data tidak ada', 500);
        }
    }
}
