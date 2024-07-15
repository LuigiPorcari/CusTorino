<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Group;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $groups = Group::all();
        $aliases = Alias::all();
        return view('dashboard.admin', compact('groups' , 'aliases'));
    }
}
