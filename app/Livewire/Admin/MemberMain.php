<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\MemberForm;
use App\Models\Group;
use App\Models\Member;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class MemberMain extends Component{

    use WithPagination;
    use WireUiActions;

    public $isOpen=false;
    public $search;
    public MemberForm $form;
    public ?Member $member;
    public $active=true;

    public function render(){
        $members=Member::where('firstname','LIKE','%'.$this->search.'%')->where('active',$this->active)->paginate();
        $groups=Group::all();
        return view('livewire.admin.member-main',compact('members','groups'));
    }

    public function confirmSimple($item): void{
        $this->dialog()->confirm([
            'title'=> '¿Seguro que deseas eliminar?',
            'icon'=> 'error',
            'method'=> 'destroy',
            'params'=> $item
        ]);
    }

    public function create(){
        $this->isOpen=true;
        $this->form->reset();
        $this->reset(['member']);
        $this->resetValidation();
    }

    public function edit(Member $member){
        // dd($member);
        $this->member=$member;
        $this->form->fill($member);
        $this->isOpen=true;
        $this->resetValidation();
    }

    public function destroy(Member $member){
        //$member->delete();
        $member->update(['active'=>false]);
        //return redirect()->route('members');
        $this->notification()->send([
            'icon' => 'info',
            'title' => 'El miembro paso al grupo inactivos',
        ]);
    }

    public function store(){
        $this->validate();
        if(!isset($this->member->id)){
            Member::create($this->form->all());
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Registro creado...',
            ]);
        }else{
            $this->member->update($this->form->all());
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Registro actualizado...',
            ]);
        }
        $this->reset(['isOpen']);
    }


    public function renovate(Member $member){
        $member->update(['active'=>true]);
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Se restauró al miembro al grupo activos',
        ]);
    }

    public function updatingSearch(){
        $this->resetPage();
    }
}
