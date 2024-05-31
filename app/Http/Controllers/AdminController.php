<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin')->except('logout');
    }
    public function showAnimals()
    {
        return view('admin.animals');
    }
}