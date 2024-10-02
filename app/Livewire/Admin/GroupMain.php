<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\GroupForm;
use App\Models\Group;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class GroupMain extends Component{

    use WithPagination;
    use WireUiActions;

    public $isOpen=false;
    public $search,$active=true;
    public GroupForm $form;
    public ?Group $group;

    public function render(){
        $groups=Group::where('name','LIKE','%'.$this->search.'%')->where('active',$this->active)->paginate();
        return view('livewire.admin.group-main',compact('groups'));
    }

    public function confirmSimple($group): void{
        $this->dialog()->confirm([
            'title'=> '¿Seguro que deseas eliminar el registro?',
            'icon'=> 'error',
            'method'=> 'destroy',
            'params'=> $group
        ]);
    }

    public function create(){
        $this->isOpen=true;
        $this->form->reset();
        $this->reset(['group']);
        $this->resetValidation();
    }

    public function edit(Group $group){
        // dd($member);
        $this->group=$group;
        $this->form->fill($group);
        $this->isOpen=true;
        $this->resetValidation();
    }

    public function destroy(Group $group){
        //$member->delete();
        $group->update(['active'=>false]);
        //return redirect()->route('members');
        $this->notification()->send([
            'icon' => 'info',
            'title' => 'El miembro paso al grupo inactivos',
        ]);
    }

    public function store(){
        $this->validate();
        if(!isset($this->group->id)){
            Group::create($this->form->all());
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Registro creado...',
            ]);
        }else{
            $this->group->update($this->form->all());
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Registro actualizado...',
            ]);
        }
        $this->reset(['isOpen']);
    }


    public function renovate(Group $group){
        $group->update(['active'=>true]);
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Se restauró al miembro al grupo activos',
        ]);
    }

    public function updatingSearch(){
        $this->resetPage();
    }

}
