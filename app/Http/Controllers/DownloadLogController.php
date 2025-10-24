<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DownloadLog;

class DownloadLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'filename' => ['nullable','string','max:255'],
            'url' => ['nullable','string','max:1000'],
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:150'],
            'role' => ['required','in:Siswa,Guru,Alumni,Orang Tua,Umum'],
            'purpose' => ['required','string','max:500'],
        ]);

        $log = DownloadLog::create([
            'filename' => $validated['filename'] ?? null,
            'url' => $validated['url'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'purpose' => $validated['purpose'],
            'ip' => $request->ip(),
        ]);

        return response()->json(['status' => 'ok', 'id' => $log->id]);
    }
}
