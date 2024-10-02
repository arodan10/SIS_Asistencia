<x-modal-card title="Registro de Grupos" wire:model.defer="isOpen">
    <div class="mx-4">
        <x-input label="Nombre de grupo" placeholder="Efeso" wire:model="form.name" />
        <div>
            <h5 class="bg-indigo-500 text-white text-sm px-2 py-1 mt-4 mb-1 rounded">Reuniones</h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <x-input label="Día" placeholder="Viernes" wire:model="form.mday" />
                <x-time-picker  label="Hora" wire:model.live="form.mtime" placeholder="5:00 PM" without-seconds/>
            </div>
            <div class="mt-2">
                <x-input label="Lugar" placeholder="Casa de Martín" wire:model="form.mplace" />
            </div>
        </div>
        <div>
            <h5 class="bg-indigo-500 text-white text-sm px-2 py-1 mt-4 mb-1 rounded">Ideales</h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <x-input label="Lema" placeholder="Escogidos para la misión" wire:model="form.motto" />
                <x-input label="Versiculo de memoria" placeholder="Filipenses 4:13" wire:model="form.text" />
            </div>
        </div>
        <div class="mt-2">
            <x-input label="Canto tema" placeholder="Solamente en Cristo" wire:model="form.song" />
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
