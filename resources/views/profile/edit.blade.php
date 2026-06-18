<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-950 leading-tight uppercase tracking-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100/40 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="p-6 sm:p-8 bg-white rounded-3xl border-4 border-slate-950 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white rounded-3xl border-4 border-slate-950 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white rounded-3xl border-4 border-slate-950 shadow-[8px_8px_0px_0px_rgba(15,23,42,1)]">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
