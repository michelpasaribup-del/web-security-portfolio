<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScannerController extends Controller
{
    public function index()
    {
        // Menampilkan halaman dashboard scanner
        return view('scanner');
    }

    public function scan(Request $request)
    {
        // Validasi input
        $request->validate([
            'target' => 'required|string',
            'tipe' => 'required|in:phishing,trojan'
        ]);

        $target = $request->input('target');
        $tipe = $request->input('tipe');
        $hasil = null;

        try {
            // Panggil API Flask (Pastikan app.py menyala di terminal)
            $response = Http::post('http://127.0.0.1:5000/api/scan', [
                'target' => $target,
                'tipe' => $tipe
            ]);

            if ($response->successful()) {
                $hasil = $response->json('hasil');
            } else {
                $hasil = "Error: Gagal menghubungi server AI.";
            }
        } catch (\Exception $e) {
            $hasil = "Error Koneksi: Pastikan Python/Flask menyala.";
        }

        // Kembalikan ke halaman scanner beserta hasil dan input sebelumnya
        return view('scanner', compact('hasil', 'target', 'tipe'));
    }
}
