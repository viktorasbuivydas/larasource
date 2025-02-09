<?php

namespace App\Filament\Resources\RepositoryResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Actions\NextAction;
use App\Filament\Resources\RepositoryResource;
use App\Filament\Resources\Actions\PreviousAction;
use App\Filament\Resources\Pages\Concerns\CanPaginateViewRecord;

class EditRepository extends EditRecord
{
    use CanPaginateViewRecord;
    protected static string $resource = RepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            PreviousAction::make(),
            NextAction::make(),
            Actions\Action::make('approve')
                ->action(function ($record) {
                    try {
                        $record->approve();
                        Notification::make()
                            ->title('Approved successfully')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error while approving the record')
                            ->error()
                            ->send();
                    }
                })
                ->color('success')
                ->visible(fn($record) => !$record->approved_at)
        ];
    }
}
