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
    public $totalPengeluaran;
    public $pendapatanBersih;
    public $chartDataPengeluaran = [];

    public $chartLabels = [];
    public $chartData = [];

    public function mount(): void
    {
        $this->kapasitasAir = DB::table('items')->where('type', 'Air Mineral')->sum(DB::raw('stok'));
        $this->stokGasTabung = DB::table('items')->where('type', 'Gas')->sum('stok');
        $this->totalPendapatan = DB::table('new_transactions')->sum('harga_total');
        $this->totalTransaksi = DB::table('new_transactions')->count();
        $this->totalPengeluaran = DB::table('other_transactions')->sum('price');
        $this->pendapatanBersih = $this->totalPendapatan - $this->totalPengeluaran;

        $filter = request('filter', 'daily');

        $data = collect();
        $now = now();

        if ($filter === 'monthly') {
            $dataPendapatan = DB::table('new_transactions')
                ->selectRaw('MONTH(created_at) as month, SUM(harga_total) as total')
                ->whereYear('created_at', $now->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $dataPengeluaran = DB::table('other_transactions')
                ->selectRaw('MONTH(created_at) as month, SUM(price) as total')
                ->whereYear('created_at', $now->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $this->chartLabels = collect(range(1, 12))->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('F'))->toArray();

            $this->chartData = collect(range(1, 12))->map(fn($m) => $dataPendapatan->get($m, 0))->toArray();
            $this->chartDataPengeluaran = collect(range(1, 12))->map(fn($m) => $dataPengeluaran->get($m, 0))->toArray();
        } elseif ($filter === 'yearly') {
            $startYear = $now->copy()->subYears(4)->year;

            $dataPendapatan = DB::table('new_transactions')
                ->selectRaw('YEAR(created_at) as year, SUM(harga_total) as total')
                ->whereYear('created_at', '>=', $startYear)
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('total', 'year');

            $dataPengeluaran = DB::table('other_transactions')
                ->selectRaw('YEAR(created_at) as year, SUM(price) as total')
                ->whereYear('created_at', '>=', $startYear)
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('total', 'year');

            $this->chartLabels = range($startYear, $now->year);
            $this->chartData = collect($this->chartLabels)->map(fn($y) => $dataPendapatan->get($y, 0))->toArray();
            $this->chartDataPengeluaran = collect($this->chartLabels)->map(fn($y) => $dataPengeluaran->get($y, 0))->toArray();
        } else {
            $daysInMonth = $now->daysInMonth;

            $dataPendapatan = DB::table('new_transactions')
                ->selectRaw('DAY(created_at) as day, SUM(harga_total) as total')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day');

            $dataPengeluaran = DB::table('other_transactions')
                ->selectRaw('DAY(created_at) as day, SUM(price) as total')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day');

            $this->chartLabels = collect(range(1, $daysInMonth))->map(fn($d) => str_pad($d, 2, '0', STR_PAD_LEFT))->toArray();
            $this->chartData = collect(range(1, $daysInMonth))->map(fn($d) => $dataPendapatan->get($d, 0))->toArray();
            $this->chartDataPengeluaran = collect(range(1, $daysInMonth))->map(fn($d) => $dataPengeluaran->get($d, 0))->toArray();
        }

    }

}
