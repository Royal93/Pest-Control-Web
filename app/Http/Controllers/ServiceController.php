<?php

namespace App\Http\Controllers;

use App\Models\Pest;
use App\Models\Industry;

class ServiceController extends Controller
{
    public function residential()
    {
        $featured = Pest::where('featured', true)->first();
        $pests = Pest::where('featured', false)->orderBy('name')->get();

        return view('services.residential', compact('featured', 'pests'));
    }

    public function pest(Pest $pest)
    {
        return view('services.pest', compact('pest'));
    }

    public function commercial()
    {
        $industries = Industry::orderBy('name')->get();

        return view('services.commercial', compact('industries'));
    }
}
