<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class GroupForm extends Form{

    #[Rule('required')]
    public $name;
    public $mday,$mtime,$mplace,$motto,$text,$song,$church_id=1;

}
