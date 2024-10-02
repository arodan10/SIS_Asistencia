<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $members=Member::factory(100)->create();
        //Member::orderBy('group_id','asc')->get();
        $groups=Group::all();
        $n=0;
        foreach ($groups as $group) {
            $members=Member::where('group_id',$group->id)->skip(0)->take(2)->get();
            $members[0]->update(['position'=>'MAESTRO(A)']);
            $members[1]->update(['position'=>'ASOCIADO(A)']);
        }
    }
}
