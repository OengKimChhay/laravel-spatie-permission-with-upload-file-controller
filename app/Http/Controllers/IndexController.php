<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class IndexController extends Controller
{
    public function index(){
        return view('admin.index');
    }
    public function users(){
        $user = User::all();
        // auth()->user()->revokePermissionTo(['create', 'view', 'update', 'delete']);
        // auth()->user()->assignRole('user');
        // auth()->user()->givePermissionTo(['create', 'view', 'update', 'delete']);
        return view('admin.user',['users' => $user]);
    }
}
