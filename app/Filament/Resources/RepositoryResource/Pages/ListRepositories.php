<?php

namespace App\Filament\Resources\RepositoryResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RepositoryResource;

class ListRepositories extends ListRecords
{
    protected static string $resource = RepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('clear_cache')
                ->action(function () {
                    Cache::flush();

                    Notification::make()
                        ->title('Cache cleared successfully')
                        ->success()
                        ->send();
                })
                ->color('success')

        ];
    }
}
