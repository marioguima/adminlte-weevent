<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;

class UploadPhoto extends Component
{

    use WithFileUploads;
    public $photo;

    public function render()
    {
        return view('livewire.user.upload-photo');
    }

    public function storagePhoto() {
        dd($this->photo);
    }
}
