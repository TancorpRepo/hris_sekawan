<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\Employee as ModelsEmployee;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class Employee extends Page implements HasTable
{
    use InteractsWithTable;
    // public $name = 'My Name is Teddy';

    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.employee';

    protected static bool $shouldRegisterNavigation = false;

    public function table(Table $table)
    {
        return $table
        ->query(Attendance::query())
        ->columns([
            TextColumn::make('PersonnelNo')->label('nik'),
            TextColumn::make('jam')->label('Tanggal & Jam'),
            TextColumn::make('status')->label('in/out'),
        ])
        ->filters([])
        ->actions([])
        ->bulkActions([]);
    }
}
