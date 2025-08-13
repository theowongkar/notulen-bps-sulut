<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Minute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Pegawai
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'Admin')->count();
        $notaryUsers = User::where('role', 'Notulis')->count();

        // Statistik Minutes per bulan (1 tahun terakhir)
        $minutesPerMonth = Minute::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Bikin array 12 bulan biar yang kosong tetap 0
        $minutes = [];
        for ($i = 1; $i <= 12; $i++) {
            $minutes[] = $minutesPerMonth->get($i, 0);
        }

        return view('dashboard.index', compact(
            'totalUsers',
            'adminUsers',
            'notaryUsers',
            'minutes'
        ));
    }
}
