<x-modal name="modalRelation" wire:model.defer="isOpenRelation" persistent>
    <x-card title="Relacionamiento">
        <div class="grid grid-cols-1 gap-4 px-4">
            <x-number label="¿Cuantos participacron de grupos pequeños en la semana?" placeholder="Nro. de participantes en grupos pequeño" wire:model="nrelationGroups" />
            <x-number label="¿Cuantos trajeron alguen nuevo este sabado?" placeholder="Nro. de miembros nuevos" wire:model="nrelationFriends" />
        </div>
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancelar" x-on:click="close()" />
            <x-button primary label="Registrar" wire:click="store_relation()" />
        </x-slot>
    </x-card>
</x-modal>
