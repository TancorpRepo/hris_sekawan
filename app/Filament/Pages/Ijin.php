<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\IconPosition;

class Ijin extends Page implements HasTable
{
    use InteractsWithTable;

    // protected static ?string $title = 'Pengganti Hari Libur';

    protected static ?string $model = \App\Models\Ijin::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.ijin';

    protected static bool $shouldRegisterNavigation = false;


    /**
     * Define the table for this page.
     *
     * @param \Filament\Tables\Table $table
     * @return \Filament\Tables\Table
     */
    public function table(Table $table)
    {
        return $table
            // ->query(\App\Models\Ijin::query())
            ->query(Ijin::query()->orderBy('created_at', 'DESC'))
            ->headerActions([
                Action::make('absensi')
                    ->label('Pengganti Hari Libur')
                    ->action(function () {
                        return redirect()->route('filament.admin.pages.ijin-page');
                    })
                    ->icon('heroicon-m-pencil-square')
                    ->iconPosition(IconPosition::After),
            ])
            ->columns([
                TextColumn::make('PersonnelNo')->label('nik'),
                TextColumn::make('jam')->label('Tanggal'),
                TextColumn::make('Keterangan')->label('Keterangan'),
            ])
            ->filters([])
            ->bulkActions([]);
    }
}
