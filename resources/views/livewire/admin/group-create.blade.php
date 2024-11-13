<x-modal-card title="Registro de Grupos" wire:model.defer="isOpen">
    <div class="mx-4">
        <x-input label="Nombre de grupo" placeholder="" wire:model="form.name" />
        <div class="mt-2">
            <x-input label="Descripción" placeholder="" wire:model="form.text" />
        </div>
        <div>
            <h5 class="bg-indigo-500 text-white text-sm px-2 py-1 mt-4 mb-1 rounded">Reuniones</h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <x-input label="Día" placeholder="" wire:model="form.mday" />
                <x-time-picker  label="Hora" wire:model.live="form.mtime" placeholder="" without-seconds/>
            </div>
            <div class="mt-2">
                <x-input label="Lugar" placeholder="Casa de Martín" wire:model="form.mplace" />
            </div>
        </div>
        {{-- <x-native-select label="Grupo" wire:model="form.church_id  ">
            <option value="1">Buenas Nuevas</option>
        </x-native-select> --}}
    </div>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-2">
            <x-button flat label="Cancelar" x-on:click="close()" />
            <x-button primary label="Registrar" wire:click="store()" />
        </div>
    </x-slot>
</x-modal-card>
