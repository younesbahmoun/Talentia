<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {
    public function index(Request $request) {
        $languages = ['PHP', 'LARAVEL', 'SQL'];
        // return view('home', compact('name', 'age'));
        // return view('home', [
        //     'name' => $request->name,
        //     'age' => $request->age,
        //     'languages' => $languages
        // ]);
        return view('home', compact('languages'));
    }
}