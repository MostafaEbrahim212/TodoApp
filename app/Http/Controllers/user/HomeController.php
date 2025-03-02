<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return view('admin.dashboard');
        }
        return view('home');
    }

}
