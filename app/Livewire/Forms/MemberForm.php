<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MemberForm extends Form
{
    #[Rule('required')]
    public $firstname,$lastname,$document;
    public $address,$cellphone,$email,$birthdate,$baptism,$position,$group_id;


}
