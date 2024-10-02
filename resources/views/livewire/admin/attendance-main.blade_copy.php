@inject('carbon', 'Carbon\Carbon')
<div class="py-2">
    <div class="mx-6 mb-4">
        <h2 class="text-3xl font-bold text-gray-800">Miembros por grupos</h2>
        <div class="border-b-2 border-info-600 w-60 mt-2"></div>
    </div>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800/50 dark:bg-gradient-to-bl">
        <div class="flex flex-wrap justify-between gap-1 mb-2">
            <div class="mt-2 ml-2">{{$dateLarge}}</div>
            <div><x-input wire:model="date" type="date"/></div>
        </div>
        <div class="flex flex-wrap justify-between gap-1">
            <!--Filtros   -->
            <div class="flex sm:w-96 w-full gap-2 items-end">
                <div class="mb-2 w-full text-gray-500">
                    {{-- <x-select placeholder="Seleccione" wire:model.live="group_id" :options="$groups" option-label="name" option-value="id"/> --}}
                    {{-- <x-select placeholder="Seleccione grupo" wire:model.live="group_id">
                        @foreach ($groups as $group)
                            <x-select.option label="{{$group->name}}" value="{{$group->id}}" />
                        @endforeach
                    </x-select> --}}
                    <x-native-select wire:model.live="group_id">
                        <option>Seleccione grupo</option>
                        @foreach ($groups as $group)
                            <option value="{{$group->id}}" class="text-xs">{{$group->name}}</option>
                        @endforeach
                    </x-native-select>
                </div>
                <div class="mb-2">
                    <x-button pink label="Relación" icon="plus" x-on:click="$openModal('modalRelation')"/>
                    @include('livewire.admin.relation-create')
                </div>
                <div class="mb-2">
                    <x-button green label="Misión" icon="plus" x-on:click="$openModal('modalMission')"/>
                    @include('livewire.admin.mission-create')
                </div>
                <div class="mb-2" wire:loading>
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>
            </div>
            <div class="mb-2 sm:w-72 w-full text-gray-500">
                <x-native-select class="text-xs">
                    @foreach ($leaders as $leader)
                        <option value="{{$leader->id}}">{{$leader->firstname}} {{$leader->lastname}} - {{$leader->position}}</option>
                    @endforeach
                </x-native-select>
            </div>
        </div>
        <!--Tabla lista de items   -->
        <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg">
            <table class="w-full table-auto">
              <thead class="bg-indigo-500 text-gray-200 border-b border-black">
                <tr class="text-left text-xs font-bold uppercase">
                  <td scope="col" class="px-6 py-3">ID</td>
                  <td scope="col" class="px-6 py-3">Nombres y apellidos</td>
                  <td scope="col" class="px-6 py-3">Celular</td>
                  <td scope="col" class="px-6 py-3">Cumpleaños</td>
                  <td scope="col" class="px-6 py-3">Bautizado</td>
                  <td scope="col" class="px-6 py-3 text-center">Estudio</td>
                </tr>
              </thead>
              <tbody wire:loading.remove class="divide-y divide-gray-200">
                @foreach($members as $member)
                <tr class="text-sm font-medium text-gray-900 even:bg-gray-100 odd:bg-white hover:bg-indigo-100">
                  <td class="px-6 py-4">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-500 text-white">
                      {{$loop->iteration}}
                      {{-- {{$member->id}} --}}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-xs">{{$member->firstname}}, {{$member->lastname}}</td>
                  <td class="px-6 py-4 text-xs">{{$member->cellphone}}</td>
                  <td class="px-6 py-4 text-xs"><i class="fa-solid fa-cake-candles text-lg {{($carbon::parse($member->birthdate)->addYears($carbon::now()->format('Y')-$carbon::parse($member->birthdate)->format('Y'))->format('Y-m-d')<=$carbon::now()->format('Y-m-d') && $carbon::parse($member->birthdate)->addYears($carbon::now()->format('Y')-$carbon::parse($member->birthdate)->format('Y'))->format('Y-m-d')>=$carbon::now()->subDay(7)->format('Y-m-d'))?' text-yellow-600':' text-gray-500'}}"></i>
                    {{($carbon::parse($member->birthdate)->addYears($carbon::now()->format('Y')-$carbon::parse($member->birthdate)->format('Y'))->format('Y-m-d')<=$carbon::now()->format('Y-m-d') && $carbon::parse($member->birthdate)->addYears($carbon::now()->format('Y')-$carbon::parse($member->birthdate)->format('Y'))->format('Y-m-d')>=$carbon::now()->subDay(7)->format('Y-m-d'))?' (SI)':' (NO)'}}
                    {{-- {{($carbon::parse($member->birthdate)->addYears(4)->format('Y-m-d')<=$carbon::now()->format('Y-m-d') && $carbon::parse($member->birthdate)->addYears(4)->format('Y-m-d')>=$carbon::now()->subDay(7)->format('Y-m-d'))?' (SI)':' (NO)'}} --}}
                    {{$carbon::parse($member->birthdate)->format('d/m')}}
                    {{-- años: {{round($carbon::parse($member->birthdate)->diffInYears($carbon::now()->format('Y-m-d'))); }} --}}
                    {{-- años: {{$carbon::now()->format('Y')-$carbon::parse($member->birthdate)->format('Y')}} --}}
                    {{-- -{{$carbon::parse($member->birthdate)->addYears(4)->format('Y-m-d')==$carbon::now()->format('Y-m-d')}}- --}}
                    {{-- {{($carbon::parse($member->birthdate)->addYears(4)->format('Y-m-d')<=$carbon::now()->format('Y-m-d') && $carbon::parse($member->birthdate)->addYears(4)->format('Y-m-d')>=$carbon::now()->subDay(7)->format('Y-m-d'))?' text-yellow-500':' text-gray-500'}} --}}
                  </td>
                  <td class="px-6 py-4 text-xs">{{$member->baptism}}</td>
                  <td class="px-6 py-4 flex gap-1 justify-end">
                    {{-- {{$attendances->contains($member->id)}} --}}
                    {{-- {{$attendances}} --}}
                    @php
                        $color1='bg-positive-100';$color2='bg-positive-200';$color3='bg-positive-300';$color4='bg-positive-400';$color5='bg-positive-500';$color6='bg-positive-600';$color7='bg-positive-700';
                        foreach ($attendances as $key=>$attendance){
                            if ($member->id==$key && $attendance=="1"){
                                $color1='bg-yellow-500';break;
                            }else{
                                $color1='bg-positive-100';
                            }
                            if ($member->id==$key && $attendance=="2"){
                                $color2='bg-yellow-500';break;
                            }else{
                                $color2='bg-positive-200';
                            }
                            if ($member->id==$key && $attendance=="3"){
                                $color3='bg-yellow-500';break;
                            }else{
                                $color3='bg-positive-300';
                            }
                            if ($member->id==$key && $attendance=="4"){
                                $color4='bg-yellow-500';break;
                            }else{
                                $color4='bg-positive-400';
                            }
                            if ($member->id==$key && $attendance=="5"){
                                $color5='bg-yellow-500';break;
                            }else{
                                $color5='bg-positive-500';
                            }
                            if ($member->id==$key && $attendance=="6"){
                                $color6='bg-yellow-500';break;
                            }else{
                                $color6='bg-positive-600';
                            }
                            if ($member->id==$key && $attendance=="7"){
                                $color7='bg-yellow-500';break;
                            }else{
                                $color7='bg-positive-700';
                            }
                        }
                    @endphp

                    <ol class="flex">
                        <li wire:click="store_study({{$member}},1)" class="{{$color1}} w-4 text-center text-gray-800 hover:scale-125 cursor-pointer">1</li>
                        <li wire:click="store_study({{$member}},2)" class="{{$color2}} w-4 text-center text-gray-700 hover:scale-125 cursor-pointer">2</li>
                        <li wire:click="store_study({{$member}},3)" class="{{$color3}} w-4 text-center text-gray-600 hover:scale-125 cursor-pointer">3</li>
                        <li wire:click="store_study({{$member}},4)" class="{{$color4}} w-4 text-center text-gray-500 hover:scale-125 cursor-pointer">4</li>
                        <li wire:click="store_study({{$member}},5)" class="{{$color5}} w-4 text-center text-gray-300 hover:scale-125 cursor-pointer">5</li>
                        <li wire:click="store_study({{$member}},6)" class="{{$color6}} w-4 text-center text-gray-200 hover:scale-125 cursor-pointer">6</li>
                        <li wire:click="store_study({{$member}},7)" class="{{$color7}} w-4 text-center text-gray-100 hover:scale-125 cursor-pointer">7</li>
                    </ol>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
        @if(!$members->count())
            <div class="text-sm mt-2">* No existe ningun registro coincidente</div>
        @endif
        {{-- <div class="px-6 py-3">
            {{$members->links()}}
        </div> --}}
        </div>
    </div>
</div>
