<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MyCustomPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.my-custom-page';
}
