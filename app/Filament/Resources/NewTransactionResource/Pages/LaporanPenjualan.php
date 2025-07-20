<?php

namespace App\Filament\Resources\NewTransactionResource\Pages;

use Filament\Forms;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\NewTransactionResource;
use App\Models\NewTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class LaporanPenjualan extends Page
{
    protected static string $resource = NewTransactionResource::class;
    protected static string $view = 'filament.resources.new-transaction-resource.pages.laporan-penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = true;

    public $from_date;
    public $to_date;
    public Collection $data;

    public static function getNavigationLabel(): string
    {
        return 'Laporan Penjualan';
    }

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->data = collect();
    }

    public function generateReport()
    {
        $this->data = NewTransaction::whereBetween('created_at', [$this->from_date, $this->to_date])->get();
    }

    public function exportPdf()
    {
        $from = $this->from_date . ' 00:00:00';
        $to = $this->to_date . ' 23:59:59';

        $data = NewTransaction::whereBetween('created_at', [$from, $to])->get();

        $pdf = Pdf::loadView('export.laporan-transaksi', [
            'data' => $data,
            'from' => $this->from_date,
            'to' => $this->to_date,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-transaksi-' . now()->format('Ymd_His') . '.pdf');
    }


    protected function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('from_date')
                ->label('Dari Tanggal')
                ->required(),
            Forms\Components\DatePicker::make('to_date')
                ->label('Sampai Tanggal')
                ->required(),
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('Generate')->action('generateReport'),
            Action::make('Download PDF')->action('exportPdf')->label('Export PDF')->color('success'),
        ];
    }
}
