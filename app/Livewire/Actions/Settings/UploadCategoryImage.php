<?php

namespace App\Livewire\Actions\Settings;

use Illuminate\Support\Facades\File;

class UploadCategoryImage
{
    public function __invoke($file)
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/categories'), $fileName);
        return 'images/categories/' . $fileName;
    }
}
