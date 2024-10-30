@section('header', __('Grupos'))
@section('section', __('Grupos de acción'))

<div>
    <x-app.card>
        <div class="flex items-center justify-between gap-2 mb-2 rounded-md">
            <div class="w-full flex gap-2">
                <!--Input de busqueda   -->
                <div class="mb-2 w-2/4">
                    <x-input wire:model.live="search" icon="user" placeholder="Buscar registro" />
                </div>
                <!--Filtros   -->
                <div class="mb-2 w-1/4 text-gray-500">
                    <x-native-select :options="[['name' => 'Activos', 'id' => 1], ['name' => 'Inactivos', 'id' => 0]]" option-label="name" option-value="id" wire:model.live="active" />
                </div>
            </div>
            <!--Boton nuevo   -->
            <div>
                <x-button gray label="Nuevo" icon="plus" wire:click="create()"></x-button>
                @include('livewire.admin.group-create')
            </div>
        </div>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($groups as $group)
                <div
                    class="w-full relative bg-gray-100 border p-4 pt-10 rounded-md shadow-lg motion-safe:hover:scale-[1.01] transition-all duration-250">
                    <div
                        class="absolute text-md left-0 top-0 bg-gray-500 text-white w-full py-1 text-center rounded-t-lg">
                        <i class="fa-solid fa-quote-left"></i> {{ $group->name }} <i
                            class="fa-solid fa-quote-right"></i>
                    </div>
                    <div class="flex flex-col gap-2 text-sm h-full">
                        <div class="flex gap-4">
                            <div class="flex items-center">
                                <i class="fa-solid fa-globe text-3xl text-cyan-700"></i>
                            </div>
                            <div class="col-span-2 flex items-center h-full">
                                <div>
                                    <div><span class="font-bold text-indigo-600">Lema: </span> {{ $group->motto }}</div>
                                    <div><span class="font-bold text-indigo-600">Texto: </span> {{ $group->text }}</div>
                                    <div><span class="font-bold text-indigo-600">Canto:</span> {{ $group->song }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-1">
                            <x-button class="w-full" positive label="Editar" xs icon="pencil" wire:click="edit({{ $group }})" />
                            @if ($active == 1)
                                <x-button class="w-full" xs label="Eliminar" negative icon="x-mark"
                                    wire:click="confirmSimple({{ $group }})" />
                            @else
                                <x-button class="w-full" warning label="Renovar" xs icon="exclamation-triangle"
                                    wire:click="renovate({{ $group }})" />
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            @if (!$groups->count())
                <x-alert title="* No existe ningun registro coincidente" info />
            @endif
        </div>

        <div class="mt-2">
            {{ $groups->links() }}
        </div>
    </x-app.card>
</div>
