@section('header', __('Miembros'))
@section('section', __('Lista de miembros'))

<div>

    @include('livewire.admin.modal.assign-role-user')

    <x-app.card>
        <div class="flex items-center justify-between gap-1 mb-2">
            <div class="w-full flex gap-2">
                <!--Input de busqueda   -->
                <div class="mb-2 w-2/4">
                    <x-input wire:model.live="search" icon="user" placeholder="Buscar registro" />
                </div>
                <!--Filtros   -->
                <div class="mb-2 w-1/4 text-gray-500">
                    <x-native-select :options="[['name' => 'Activos', 'id' => 1], ['name' => 'Inactivos', 'id' => 0]]" option-label="name" option-value="id" wire:model.live="active" />
                </div>
                <div class="mb-2" wire:loading>
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                </div>
            </div>
            <!--Boton nuevo   -->
            <div class="mb-1 ml-10">
                <x-button gray label="Nuevo" icon="plus" wire:click="create()"></x-button>
                @if ($isOpen)
                    @include('livewire.admin.member-create')
                @endif
            </div>
        </div>
        <!--Tabla lista de items   -->
        <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg">
            <table class="w-full table-auto">
                <thead class="bg-gray-500 text-gray-200">
                    <tr class="text-left text-xs font-bold uppercase">
                        <td scope="col" class="px-6 py-3">ID</td>
                        <td scope="col" class="px-6 py-3">Nombres y apellidos</td>
                        <td scope="col" class="px-6 py-3">Celular</td>
                        <td scope="col" class="px-6 py-3">Cumpleaños</td>
                        <td scope="col" class="px-6 py-3">F. Contrato</td>
                        <td scope="col" class="px-6 py-3 text-center">Opciones</td>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($members as $item)
                        <tr class="text-sm text-gray-900 even:bg-gray-50 odd:bg-white hover:bg-gray-100">
                            <td class="px-6 py-2">
                                <span
                                    class="px-2 inline-flex leading-5 font-semibold rounded-full bg-gray-500 text-white">
                                    {{ $item->id }}
                                </span>
                            </td>
                            <td class="px-6 py-2">{{ $item->firstname }}, {{ $item->lastname }}</td>
                            <td class="px-6 py-2">{{ $item->cellphone }}</td>
                            <td class="px-6 py-2">{{ $item->birthdate }}</td>
                            <td class="px-6 py-2">{{ $item->baptism }}</td>
                            <td class="px-6 py-2 flex gap-1 justify-end">
                                @can('admin.users.assign-role')
                                    <x-button-tooltip hover="gray"
                                        content="{{ $item->roles()->count() > 0 ? 'Editar Rol' : 'Asignar Rol' }}"
                                        wire:click="showRoles({{ $item }})">
                                        <i class="fa-solid fa-user-lock fa-fw"></i>
                                    </x-button-tooltip>
                                @endcan
                                <x-mini-button rounded primary icon="pencil" wire:click="edit({{ $item }})" />
                                @if ($active == 1)
                                    <x-mini-button rounded negative icon="x-mark"
                                        wire:click="confirmSimple({{ $item }})" />
                                @else
                                    <x-mini-button rounded warning icon="exclamation-triangle"
                                        wire:click="renovate({{ $item }})" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2">
                @if (!$members->count())
                    <x-alert title="* No existe ningun registro coincidente" info />
                @endif
            </div>
        </div>
        <div class="px-6 py-3">
            {{ $members->links() }}
        </div>
    </x-app.card>
</div>
