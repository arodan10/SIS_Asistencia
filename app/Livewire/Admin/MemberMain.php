<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\MemberForm;
use App\Models\Group;
use App\Models\Member;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use WireUi\Traits\WireUiActions;

class MemberMain extends Component{

    use WithPagination;
    use WireUiActions;

    public $isOpen = false, $isOpenAssign = false, $showUser = false, $isOpenDelete = false;
    public $itemId, $userState, $userRol;
    public $search;
    public MemberForm $form;
    public ?Member $member;
    public $active=true;
    public $roles, $listRoles = [];

    public function render(){
        $this->roles = Role::all();
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

    public function showRoles(Member $member)
    {
        $this->isOpenAssign = true;
        $this->member = $member;
        $this->listRoles = $member->roles->pluck('id')->toArray();
    }

    public function updateRoleUser(Member $member)
    {
        $isNewAssignment = $member->roles()->count() === 0;
        if ($isNewAssignment && empty($this->listRoles)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'No se puede asignar roles vacíos',
            ]);
        } else {
            $member->roles()->sync($this->listRoles);
            if ($isNewAssignment && $this->listRoles) {
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Se asignaron correctamente los roles',
                ]);
            } else if (!$isNewAssignment) {
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Se actualizaron correctamente los roles',
                ]);
            }
        }
        $this->reset(['isOpenAssign']);
    }

    public function closeModals()
    {
        $this->isOpen = false;
        $this->isOpenAssign = false;
        $this->showUser = false;
        $this->isOpenDelete = false;
    }
}
