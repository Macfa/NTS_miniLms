<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Program;

class MainController extends Controller
{
    public function index()
    {
      $programs = Program::with('chapters')->activeProgram()->get();
      return view('main/index', compact('programs'));
    }
}