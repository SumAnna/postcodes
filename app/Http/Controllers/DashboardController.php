<?php

namespace App\Http\Controllers;

use App\Enums\UserEnum;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboard', ['user' => $user]);
    }
}

