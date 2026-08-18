<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Pest;
use App\Models\Industry;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $featured = Pest::where('featured', true)->first();
        $pests = Pest::where('featured', false)->orderBy('name')->take(6)->get();
        $industries = Industry::orderBy('name')->take(3)->get();

        return view('home', compact('plans', 'featured', 'pests', 'industries'));
    }
}
