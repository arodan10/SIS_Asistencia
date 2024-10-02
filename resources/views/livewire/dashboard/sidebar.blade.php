<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-indigo-700 lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <img src="assets/img/logo.png" class="w-full">
            {{-- <span class="mx-2 text-2xl font-semibold text-white">ProyectoEfeso</span> --}}
        </div>
    </div>
    <nav class="mt-2">|
        <x-nav-sidebar :active="request()->routeIs('dashboard')" class="flex items-center px-6 py-2 mt-4 text-gray-400 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            href="{{ route('dashboard') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
            </svg>
            <span class="mx-3">Dashboard</span>
        </x-nav-sidebar>

        <x-nav-sidebar :active="request()->routeIs('groups')" class="flex items-center px-6 py-2 mt-4 text-gray-400 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            href="{{ route('groups') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
            </svg>
            <span class="mx-3">Grupos</span>
        </x-nav-sidebar>

        <x-nav-sidebar :active="request()->routeIs('members')" class="flex items-center px-6 py-2 mt-4 text-gray-400 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            href="{{ route('members') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
              </svg>
            <span class="mx-3">Miembros</span>
        </x-nav-sidebar>

        <x-nav-sidebar :active="request()->routeIs('attendance')" class="flex items-center px-6 py-2 mt-4 text-gray-400 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
            href="{{ route('attendance') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                </path>
            </svg>
            <span class="mx-3">Registro</span>
        </x-nav-sidebar>

    </nav>
</div>
