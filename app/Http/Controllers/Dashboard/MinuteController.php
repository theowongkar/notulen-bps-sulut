<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Minute;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class MinuteController extends Controller
{
    public function index(Request $request)
    {
        // Validasi Search Form
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'search' => 'nullable|string|max:50',
        ]);

        // Ambil Nilai
        $start_date = $validated['start_date'] ?? null;
        $end_date = $validated['end_date'] ?? null;
        $search = $validated['search'] ?? null;

        // Ambil semua notulen
        $minutes = Minute::with('user')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('problem', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('dashboard.minutes.index', compact('minutes'));
    }

    public function create()
    {
        // Ambil Data Pengguna
        $users = User::all();

        return view('dashboard.minutes.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Validasi Input
        $validated = $request->validate([
            'problem' => 'required|string',
            'solution' => 'required|string',
            'follow_up_plan' => 'required|string',
            'data_source' => 'required|string',
            'followed_up_by' => 'required|exists:users,id',
            'follow_up_limits' => 'required|date',
            'evidence' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload file evidence jika ada
        if ($request->hasFile('evidence')) {
            $validated['evidence'] = $request->file('evidence')->store('minutes', 'public');
        }

        // Simpan data (slug akan otomatis dibuat oleh model Sluggable)
        Minute::create($validated);

        return redirect()->back()->with('success', 'Notulen berhasil ditambahkan.');
    }

    public function printPDF(string $slug)
    {
        // Ambil data laporan berdasarkan slug
        $minute = Minute::with('user')->where('slug', $slug)->firstOrFail();

        // Generate PDF menggunakan view
        $pdf = Pdf::loadView('dashboard.minutes.pdf', compact('minute'));

        // Mengunduh PDF
        return $pdf->stream("Notulen.pdf");
    }

    public function edit(string $slug)
    {
        // Ambil notulen berdasarkan slug
        $minute = Minute::with('user')->where('slug', $slug)->firstOrFail();

        // Cek Izin
        Gate::authorize('update-minute', $minute);

        // Ambil Data Pengguna
        $users = User::all();

        return view('dashboard.minutes.edit', compact('minute', 'users'));
    }

    public function update(Request $request, string $slug)
    {
        // Ambil notulen berdasarkan slug
        $minute = Minute::where('slug', $slug)->firstOrFail();

        // Cek Izin
        Gate::authorize('update-minute', $minute);

        // Validasi Input
        $validated = $request->validate([
            'evidence' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'problem' => 'required|string',
            'solution' => 'required|string',
            'follow_up_plan' => 'required|string',
            'data_source' => 'required|string',
            'followed_up_by' => 'required|exists:users,id',
            'follow_up_limits' => 'required|date',
        ]);

        // Cek kalau ada file evidence baru diupload
        if ($request->hasFile('evidence')) {
            if ($minute->evidence && Storage::disk('public')->exists($minute->evidence)) {
                Storage::disk('public')->delete($minute->evidence);
            }
            $validated['evidence'] = $request->file('evidence')->store('minutes', 'public');
        } else {
            $validated['evidence'] = $minute->evidence;
        }

        // Update Notulen
        $minute->update($validated);

        return redirect()->back()->with('success', 'Notulen berhasil diperbarui.');
    }

    public function destroy(string $slug)
    {
        // Ambil notulen berdasarkan slug
        $minute = Minute::where('slug', $slug)->firstOrFail();

        // Cek Izin
        Gate::authorize('delete-minute', $minute);

        // Hapus file evidence jika ada
        if ($minute->evidence && Storage::disk('public')->exists($minute->evidence)) {
            Storage::disk('public')->delete($minute->evidence);
        }

        // Hapus data dari database
        $minute->delete();

        return redirect()->route('dashboard.minute.index')->with('success', 'Notulen berhasil dihapus.');
    }
}
