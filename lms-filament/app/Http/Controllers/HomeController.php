<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user) {
            if (in_array($user->role, ['admin', 'teacher'])) {
                return redirect('/admin');
            } else {
                return redirect()->route('student.dashboard');
            }
        }
        
        return redirect('/login');
    }
}