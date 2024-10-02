<?php

namespace App\Livewire\Dashboard;

use App\Models\Member;
use App\Models\Result;
use Livewire\Attributes\On;
use Livewire\Component;

class Main extends Component{

    public array $packageStatuses = [];

    public function render(){
        $totalMembers=Member::count();
        $totalCommunion=Result::where('indicator_id',1)->sum('quantity');
        $totalRelation=Result::where('indicator_id',2)->orWhere('indicator_id',3)->sum('quantity');
        $rpgroups=Result::where('indicator_id',2)->sum('quantity');
        $rfriends=Result::where('indicator_id',3)->sum('quantity');
        $totalMission=Result::where('indicator_id',4)->orWhere('indicator_id',5)->orWhere('indicator_id',6)->sum('quantity');
        $mestudy=Result::where('indicator_id',4)->sum('quantity');
        $mvisits=Result::where('indicator_id',5)->sum('quantity');
        $mpublications=Result::where('indicator_id',6)->sum('quantity');
        $barData = $this->generateRandomData(5);
        $pieData = $this->generateRandomData(3);
        return view('livewire.dashboard.main',
        compact('totalMembers','totalCommunion','totalRelation','totalMission',
        'rpgroups','rfriends','mestudy','mvisits','mpublications','barData','pieData'));
    }

    #[On('echo:termometer,DashboardSent')]
    public function onRelationSent($event){
        $this->packageStatuses[] = $event;
        $this->render();
    }

    private function generateRandomData($count){
        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $data[] = rand(1, 10);
        }
        return $data;
    }
}
