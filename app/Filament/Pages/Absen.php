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
        $loginAdmin = auth()->user()->is_admin;

        if ($loginAdmin == '1') {
            return (string) ($record->nik ?? $record->id ?? throw new \Exception('Record key is null'));
        }

        // Untuk non-admin (hasil query agregat), pakai tanggal sebagai key
        return (string) ($record->tanggal ?? throw new \Exception('Record key is null'));
    }

    //! Belum ada filter tanggal awal & tanggal akhir
    public function table(Table $table)
    {
        // $loginUser = auth()->user()->PersonnelNo;
        $loginUser = auth()->user()->PersonnelNo;
        $loginAdmin = auth()->user()->is_admin;

        // Admin(12345)
        if ($loginAdmin == 1) {
            // return $table->query(Attendance::where('mesin', 'HP')->query()->orderBy('jam', 'DESC'))
            return $table->query(
                Attendance::where('mesin', 'HP')
                    ->whereIn('status', [1, 2])   // ✔ hanya status 1 & 2
                    ->orderBy('jam', 'DESC')
            )
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
                    TextColumn::make('nik')->label('NIK'),
                    TextColumn::make('jam')->label('Tanggal & Jam'),
                    TextColumn::make('Lokasi')->label('Lokasi')->wrap(),
                    // TextColumn::make('status')->label('in/out'),
                    TextColumn::make('status')
                        ->label('Masuk/Pulang')
                        ->badge()
                        ->color(fn($state) => match ($state) {
                            '1' => 'success',
                            '2' => 'warning',
                            default => 'gray',
                        })
                        ->formatStateUsing(fn($state) => match ($state) {
                            '1' => 'Masuk',
                            '2' => 'Pulang',
                            default => 'Absen di mesin',
                        }),
                ])
                ->filters([])
                ->bulkActions([]);
        }

        return $table
            ->query(
                Attendance::selectRaw('
                DATE(jam) as tanggal,
                MIN(CASE WHEN status = 1 THEN TIME(jam) END) as jam_masuk,
                MIN(CASE WHEN status = 2 THEN TIME(jam) END) as jam_pulang
            ')
                    ->where('nik', $loginUser)
                    ->where('mesin', 'HP')
                    ->groupByRaw('DATE(jam)')
                    ->orderByRaw('DATE(jam) DESC')
            )
            ->headerActions([
                Action::make('absensi')
                    ->label('Absensi')
                    ->action(function () {
                        return redirect()->route('filament.admin.pages.absen-page');
                    })
                    ->icon('heroicon-m-pencil-square')
                    ->iconPosition(IconPosition::After),
            ])
            ->columns([
                TextColumn::make('tanggal')->label('Tanggal'),
                TextColumn::make('jam_masuk')->label('Jam Masuk'),
                TextColumn::make('jam_pulang')
                    ->label('Jam Pulang')
                    ->formatStateUsing(fn($state) => $state ?? '-'),
            ])
            ->filters([])
            ->bulkActions([]);

        if (!auth()->user()->PersonnelNo) {
            throw new \Exception('NIK tidak ditemukan untuk pengguna yang login.');
        }
    }
}
