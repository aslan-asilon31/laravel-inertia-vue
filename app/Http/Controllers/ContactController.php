<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contact', [
            'title' => 'Tentang Kami (Dari Controller)',
            'description' => 'Halaman ini sekarang dirender melalui PageController.',
        ]);
    }

    // Menangani pengiriman data form dari halaman Contact
    public function sendContact(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Simpan ke database atau kirim email di sini...

        // Redirect kembali ke halaman contact dengan flash message sukses
        return redirect()->route('contact')->with('success', 'Pesan Anda berhasil dikirim lewat Controller!');
    }
}
