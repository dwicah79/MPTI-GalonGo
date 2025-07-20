<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.dashboard';

    public $kapasitasAir;
    public $stokGasTabung;
    public $totalPendapatan;
    public $totalTransaksi;
    public $chartLabels = [];
    public $chartData = [];

    public function mount(): void
    {
        $this->kapasitasAir = DB::table('items')->where('type', 'Air Mineral')->sum(DB::raw('stok'));
        $this->stokGasTabung = DB::table('items')->where('type', 'Gas')->sum('stok');
        $this->totalPendapatan = DB::table('new_transactions')->sum('harga_total');
        $this->totalTransaksi = DB::table('new_transactions')->count();

        $filter = request('filter', 'daily');

        $data = collect();
        $now = now();

        if ($filter === 'monthly') {
            // Ambil transaksi bulanan per bulan dalam tahun ini
            $data = DB::table('new_transactions')
                ->selectRaw('MONTH(created_at) as month, SUM(harga_total) as total')
                ->whereYear('created_at', $now->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $this->chartLabels = collect(range(1, 12))->map(function ($m) {
                return \Carbon\Carbon::create()->month($m)->format('F');
            })->toArray();

            $this->chartData = collect(range(1, 12))->map(fn($m) => $data->get($m, 0))->toArray();
        } elseif ($filter === 'yearly') {
            // Ambil transaksi 5 tahun terakhir
            $startYear = $now->copy()->subYears(4)->year;

            $data = DB::table('new_transactions')
                ->selectRaw('YEAR(created_at) as year, SUM(harga_total) as total')
                ->whereYear('created_at', '>=', $startYear)
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('total', 'year');

            $this->chartLabels = range($startYear, $now->year);
            $this->chartData = collect($this->chartLabels)->map(fn($y) => $data->get($y, 0))->toArray();
        } else {
            // Harian dalam bulan ini
            $daysInMonth = $now->daysInMonth;

            $data = DB::table('new_transactions')
                ->selectRaw('DAY(created_at) as day, SUM(harga_total) as total')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day');

            $this->chartLabels = collect(range(1, $daysInMonth))->map(fn($d) => str_pad($d, 2, '0', STR_PAD_LEFT))->toArray();
            $this->chartData = collect(range(1, $daysInMonth))->map(fn($d) => $data->get($d, 0))->toArray();
        }
    }

}
