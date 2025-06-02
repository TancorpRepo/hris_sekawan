<?php

namespace App\Filament\Pages;

use App\Filament\Exports\AbsenExporter;
use App\Models\Attendance;
use App\Models\Daftar;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\ExportAction;

class Absen extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Absensi';

    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.absen';

    public function modal(Table $table)
    {
        return $table
            ->query(Daftar::query())
            ->paginated(false)
            ->columns([
                TextColumn::make('keterangan')->label('Keterangan'),
            ])
            ->filters([])
            ->actions([
                Action::make('settings')
                    ->label('Absensi')
                    ->action(function () {
                        return redirect()->route('filament.admin.pages.absen-page');
                    }),
            ])
            ->bulkActions([]);
    }

    public function getTableRecordKey($record): string
    {
        $key = $record->PersonnelNo; // Atau kolom yang sesuai

        if ($key === null) {
            // Tangani kasus ketika key tidak ada, misalnya dengan menghasilkan ID default atau error
            throw new \Exception('Record key is null');
        }

        return (string) $key;
    }

    //! Belum ada filter tanggal awal & tanggal akhir
    public function table(Table $table)
    {
        $loginUser = auth()->user()->PersonnelNo;
        $loginAdmin = auth()->user()->is_admin;

        // Admin(12345)
        if ($loginAdmin == '1') {
            return $table->query(Attendance::query()->orderBy('CurrentDateTime', 'DESC'))
                ->headerActions([
                    Action::make('absensi')
                        ->label('Absensi')
                        ->action(function () {
                            redirect()->route('filament.admin.pages.absen-page');
                        })
                        ->icon('heroicon-m-pencil-square')
                        ->iconPosition(IconPosition::After),
                    // ExportAction::make()
                    //     ->label('Export Absensi')
                    //     ->exporter(AbsenExporter::class),
                    ExportAction::make()
                    ->label('Export Absensi')
                    ->form([
                        DatePicker::make('start_date')
                        ->label('Tanggal Awal')
                        ->required(),
                        DatePicker::make('end_date')
                        ->label('Tanggal Akhir')
                        ->required(),
                    ])
                    ->action(function (array $data) {
                        $start_date = $data['start_date'];
                        $end_date = $data['end_date'];

                        return AbsenExporter::exportWithDateRange($start_date, $end_date);
                    })
                ])
                ->columns([
                    TextColumn::make('PersonnelNo')->label('NIK'),
                    TextColumn::make('CurrentDateTime')->label('Tanggal & Jam'),
                    TextColumn::make('CheckType')->label('in/out'),
                ])
                ->filters([])
                ->bulkActions([]);
        }

        return $table
            ->query(Attendance::query()
                // ->where('PersonnelNo', auth()->user()->PersonnelNo)
                ->where('PersonnelNo', $loginUser)
                ->orderBy('CurrentDateTime', 'DESC'))

            // ->headerActionsPosition('start')
            ->headerActions([
                Action::make('absensi')
                    ->label('Absensi')
                    ->action(function () {
                        return redirect()->route('filament.admin.pages.absen-page');
                    })
                    ->icon('heroicon-m-pencil-square')
                    ->iconPosition(IconPosition::After),
                // ExportAction::make()->label('Export Absensi')->exporter(AbsenExporter::class),
            ])

            ->columns([
                TextColumn::make('PersonnelNo')->label('nik'),
                TextColumn::make('CurrentDateTime')->label('Tanggal & Jam'),
                TextColumn::make('CheckType')->label('in/out'),
            ])
            ->filters([])
            ->bulkActions([]);

        if (!auth()->user()->PersonnelNo) {
            throw new \Exception('NIK tidak ditemukan untuk pengguna yang login.');
        }
    }
}
