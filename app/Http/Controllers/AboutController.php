<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index()
    {
        return Inertia::render('About', [
            'title' => 'Tentang Kami (Dari Controller)',
            'description' => 'Halaman ini sekarang dirender melalui PageController.',
        ]);
    }
}
