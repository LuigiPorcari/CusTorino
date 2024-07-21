<?php

namespace App\Http\Controllers;

use App\Models\Alias;
use App\Models\Group;
use App\Models\Trainer;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $groups = Group::paginate(1);
        $aliases = Alias::all();
        $trainers = Trainer::paginate(5);
        return view('dashboard.admin', compact('groups' , 'aliases' , 'trainers'));
    }
}
