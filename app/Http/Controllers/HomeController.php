<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $users = [];
        if ($request->user()->isAdmin()) {
            $users = User::List();
        }

        return view('home', compact('users'));
    }
}
