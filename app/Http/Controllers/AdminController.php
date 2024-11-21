<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    function index()
    {
        if (Auth::user()->level == "admin") {
            return $this->admin();
        } elseif (Auth::user()->level == "stocker") {
            return $this->stocker();
        } elseif (Auth::user()->level == "purchaser") {
            return $this->purchaser();
        } elseif (Auth::user()->level == "sales") {
            return $this->sales();
        } else {
            return view('welcome');
        }
    }

    function admin()
    {
        $role = Auth::user()->role;
        return view('welcome');
    }
    function stocker()
    {
        return view('welcome');
    }
    function purchaser()
    {
        return view('welcome');
    }
    function sales()
    {
        return view('welcome');
    }
}