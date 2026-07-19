@extends('layouts.main')

@section('title', 'SSO — ' . ($client->name ?? 'Masuk'))

<style>
    #sidebar, header, footer { display: none !important; }
    main { margin-left: 0 !important; }
</style>

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="max-w-md w-full">

        {{-- SSO Header --}}
        <div class="text-center mb-8">
            <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-4">
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Universitas Markandeya</h1>
            <p class="text-sm text-gray-500 mt-1">Pusat Autentikasi Terpadu (SSO)</p>
        </div>

        {{-- Client App Card --}}
        @if(isset($client))
        <div class="mb-6 flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4">
            @if($client->logo)
                <img src="{{ $client->logo }}" alt="{{ $client->name }}" class="h-10 w-10 rounded-xl object-contain">
            @else
                <div class="h-10 w-10 rounded-xl bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-sm"></i>
                </div>
            @endif
            <div>
                <p class="text-xs text-gray-500">Masuk untuk mengakses</p>
                <p class="font-bold text-gray-800">{{ $client->name }}</p>
            </div>
        </div>
        @endif

        {{-- Error --}}
        @if(isset($error) || $errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium flex items-start gap-2">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <span>{{ $error ?? $errors->first() }}</span>
        </div>
        @endif

        {{-- Role badge --}}
        @if(isset($allowed_roles) && count($allowed_roles))
        <div class="mb-4 flex gap-2 flex-wrap">
            <span class="text-xs text-gray-500 self-center">Akses untuk:</span>
            @foreach($allowed_roles as $role)
            <span class="text-xs font-bold px-3 py-1 rounded-full
                {{ $role === 'dosen' ? 'bg-purple-100 text-purple-700' : '' }}
                {{ $role === 'mahasiswa' ? 'bg-emerald-100 text-emerald-700' : '' }}
                {{ $role === 'admin' ? 'bg-orange-100 text-orange-700' : '' }}">
                <i class="fas {{ $role === 'dosen' ? 'fa-chalkboard-teacher' : ($role === 'mahasiswa' ? 'fa-user-graduate' : 'fa-user-shield') }} mr-1"></i>
                {{ ucfirst($role) }}
            </span>
            @endforeach
        </div>
        @endif

        {{-- Login Form --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">
            <form action="{{ route('sso.authenticate') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="client_id"    value="{{ $client->client_id ?? '' }}">
                <input type="hidden" name="redirect_uri" value="{{ $redirect_uri ?? '' }}">
                <input type="hidden" name="state"        value="{{ $state ?? '' }}">

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">
                        @php
                            $roles = $allowed_roles ?? [];
                            if (in_array('dosen', $roles) && !in_array('mahasiswa', $roles)) $hint = 'NIDN';
                            elseif (in_array('mahasiswa', $roles) && !in_array('dosen', $roles)) $hint = 'NIM / Email';
                            else $hint = 'NIM / NIDN / Email';
                        @endphp
                        {{ $hint }}
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-id-card text-sm"></i>
                        </div>
                        <input type="text" name="credential" id="credential"
                            value="{{ old('credential') }}"
                            required autofocus autocomplete="username"
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all"
                            placeholder="Masukkan {{ $hint }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            required autocomplete="current-password"
                            class="block w-full pl-11 pr-11 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-500 transition-colors">
                            <i class="fas fa-eye text-sm" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-[0.98]">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-50 text-center space-y-2">
                <p class="text-xs text-gray-400">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Autentikasi aman melalui SSO Universitas Markandeya
                </p>
                <p class="text-xs text-gray-400">
                    Masalah login? Hubungi
                    <a href="mailto:admin@markandeyabali.ac.id" class="text-blue-500 hover:underline">admin@markandeyabali.ac.id</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} Universitas Markandeya · Sistem SSO Terpadu
        </p>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
