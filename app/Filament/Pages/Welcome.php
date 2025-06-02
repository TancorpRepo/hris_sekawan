<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Welcome extends Page
{
    public $name = 'Welcome';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.welcome';

    protected static bool $shouldRegisterNavigation = false;
}
