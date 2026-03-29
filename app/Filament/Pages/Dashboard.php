<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getTitle(): string
    {
        return 'لوحة التحكم';
    }

    public static function getNavigationLabel(): string
    {
        return 'لوحة التحكم';
    }
}
