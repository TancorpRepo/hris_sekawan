<?php

namespace App\Filament\Exports;

use App\Models\Ijin;
use App\Models\Pengganti;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;


class PenggantiExporter extends Exporter
{
    protected Collection $data;

    protected static ?string $model = Ijin::class;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public static function make(Collection $data)
    {
        return new self($data);
}

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('PersonnelNo')->label('nik'),
            ExportColumn::make('CurrentDateTime')->label('Tanggal'),
            ExportColumn::make('Keterangan')->label('Keterangan'),
        ];
    }

    public function export()
    {
        $columns = self::getColumns();

        // Sorting the data by 'CurrentDateTime' in descending order
        $exportedData = $this->data->sortByDesc('created_at')->map(function ($row) use ($columns) {
            return collect($columns)->mapWithKeys(function ($column) use ($row) {
                $columnName = $column->getName();
                // Ensure we don't get an error if a value is null or missing
                return [$column->getLabel() => $row->{$columnName} ?? ''];
            });
        });

        // Handle data ketika null value
        if ($exportedData->isEmpty()) {

            return Excel::download(new class implements FromCollection, WithHeadings {
                public function collection()
                {
                    return collect(); // Kembalikan data null
                }

                public function headings(): array
                {
                    return [];
                }
            }, 'Data_Pengganti_Hari_Libur.xlsx');
        }

        // Proceed with export if data is available
        $exportClass = new class($exportedData) implements FromCollection, WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            // Data untuk baris
            public function collection()
            {
                return $this->data;
            }

            // Header kolom
            public function headings(): array
            {
                // return collect(self::getColumns())->pluck('label')->toArray();
                return array_keys($this->data->first()->toArray() ?? []);
            }
        };

        // Return the Excel download response
        return Excel::download($exportClass, 'Data_Pengganti_Hari_Libur.xlsx');
    }

    public static function exportWithDateRange($startDate, $endDate)
    {
        if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
            throw new \Exception('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
        }

        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        $data = Ijin::whereBetween('created_at', [$startDateTime, $endDateTime])->get();

        if ($data->isEmpty()) {
            // Menampilkan notifikasi jika tidak ada data
            session()->flash('message', 'Tidak ada data pada rentang tanggal yang dipilih.');
            return redirect()->back();
        }

        $exporter = new self($data);

        return $exporter->export();
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pengganti_hari_libur export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
