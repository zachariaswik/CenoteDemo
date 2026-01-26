<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Http\Requests\Admin\StoreUserRequest;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Validator;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Mutate form data before creating.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $request = new StoreUserRequest;
        $validator = Validator::make($data, $request->rules(), $request->messages());
        $validator->validate();

        return $data;
    }
}
