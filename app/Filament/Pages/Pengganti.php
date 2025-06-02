<?php

namespace App\Filament\Pages;

use App\Filament\Exports\PenggantiExporter;
use App\Models\Ijin;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables;

class Pengganti extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $title = 'Pengganti Hari Libur';

    protected static ?string $model = Ijin::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.pengganti-hari-libur';

    /**
     * Define the table for this page.
     *
     * @param \Filament\Tables\Table $table
     * @return \Filament\Tables\Table
     */
    public function table(Table $table)
    {
        $loginUser = auth()->user()->PersonnelNo;
        $loginAdmin = auth()->user()->is_admin;

        // Admin(12345)
        if ($loginAdmin == '1') {
            return $table->query(Ijin::query()->orderBy('created_at', 'DESC'))
                ->headerActions([
                    Action::make('absensi')
                        ->label('Pengganti Hari Libur')
                        ->action(function () {
                            return redirect()->route('filament.admin.pages.pengganti-page');
                        })
                        ->icon('heroicon-m-pencil-square')
                        ->iconPosition(IconPosition::After),
                    ExportAction::make()
                        ->label('Export Pengganti Hari Libur')
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

                            return PenggantiExporter::exportWithDateRange($start_date, $end_date);
                        })
                ])
                ->columns([
                    TextColumn::make('PersonnelNo')->label('nik'),
                    TextColumn::make('CurrentDateTime')->label('Tanggal'),
                    TextColumn::make('Keterangan')->label('Keterangan'),
                ])
                ->actions([
                    // ...
                    Tables\Actions\DeleteAction::make()->label('Batal'),
                ])
                ->filters([])
                ->bulkActions([]);
        }

        return $table
            ->query(Ijin::query()
                ->where('PersonnelNo', $loginUser)
                ->orderBy('created_at', 'DESC'))
            ->headerActions([
                Action::make('absensi')
                    ->label('Pengganti Hari Libur')
                    ->action(function () {
                        return redirect()->route('filament.admin.pages.pengganti-page');
                    })
                    ->icon('heroicon-m-pencil-square')
                    ->iconPosition(IconPosition::After),
            ])
            ->columns([
                TextColumn::make('PersonnelNo')->label('nik'),
                TextColumn::make('CurrentDateTime')->label('Tanggal'),
                TextColumn::make('Keterangan')->label('Keterangan'),
            ])
            ->actions([
                // ...
                Tables\Actions\DeleteAction::make()->label('Batal')
            ])
            ->filters([])
            ->bulkActions([]);

        if (!auth()->user()->PersonnelNo) {
            throw new \Exception('NIK tidak ditemukan untuk pengguna yang login.');
        }
    }
}
