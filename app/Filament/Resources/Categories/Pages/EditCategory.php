<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action) {
                    if ($this->record->articles()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Cannot delete category')
                            ->body('This category has ' . $this->record->articles()->count() . ' article(s). Please reassign or delete them first.')
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
