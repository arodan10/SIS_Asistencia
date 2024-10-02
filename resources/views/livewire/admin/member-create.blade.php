<x-modal-card title="Registro de Miembro" wire:model.defer="isOpen">
    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 px-3">
        <x-input label="Nombres" placeholder="First Name" wire:model="form.firstname" />
        <x-input label="Apellidos" placeholder="Last Name" wire:model="form.lastname" />
        <div class="col-span-1 space-y-6 sm:col-span-2 sm:grid sm:grid-cols-7 sm:gap-2 sm:space-y-0">
            <div class="col-span-1 sm:col-span-2">
                <x-input label="DNI" placeholder="Document" wire:model="form.document" />
            </div>
            <div class="col-span-1 sm:col-span-5">
                <x-input label="Dirección" placeholder="Address" wire:model="form.address" />
            </div>
        </div>
        <div class="col-span-1 space-y-6 sm:col-span-2 sm:grid sm:grid-cols-7 sm:gap-2 sm:space-y-0">
            <div class="col-span-1 sm:col-span-2">
                <x-input label="Celular" placeholder="950124578" wire:model="form.cellphone" />
            </div>
            <div class="col-span-1 sm:col-span-5">
                <x-input label="Correo electrónico" placeholder="example@mail.com" wire:model="form.email" />
            </div>
        </div>

        <x-input type="date" icon="chevron-right" label="Nacimiento" wire:model="form.birthdate"/>
        <x-input type="date" icon="chevron-right" label="Bautismo" wire:model="form.baptism"/>
        {{-- <x-select label="Grupo" placeholder="Seleccione" wire:model="form.group_id" :options="$groups" /> --}}
        <x-native-select label="Grupo" wire:model="form.group_id">
            <option>Seleccione opción</option>
            @foreach ($groups as $group)
            <option value="{{$group->id}}">{{$group->name}}</option>
            @endforeach
        </x-native-select>
        <x-native-select label="Tipo" wire:model="form.position">
            <option>Seleccione opción</option>
            <option value="MIEMBRO">MIEMBRO</option>
            <option value="MAESTRO(A)">MAESTRO(A)</option>
            <option value="ASOCIADO(A)">ASOCIADO(A)</option>
        </x-native-select>
        {{-- <x-select label="Tipo" placeholder="Seleccione" :options="['MAESTRO(A)','ASOCIADO(A)']" wire:model="form.position" /> --}}
    </div>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-2">
            <x-button flat label="Cancelar" x-on:click="close()" />
            <x-button primary label="Registrar" wire:click="store()" />
        </div>
    </x-slot>
</x-modal-card>
