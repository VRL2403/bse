<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function companies(Request $request)
    {
        $companies = Companies::where('status', '=', 1)
            ->orderBy('company_name', 'asc')
            ->get();
        return view('companies', compact('companies'));
    }
}
