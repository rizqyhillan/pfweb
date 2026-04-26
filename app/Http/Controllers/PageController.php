<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function doctors()
    {
        // TODO: Fetch doctors from database
        return view('pages.doctors');
    }

    public function services()
    {
        // TODO: Fetch services from database
        return view('pages.services');
    }

    public function departments()
    {
        // TODO: Fetch departments from database
        return view('pages.departments');
    }
}
