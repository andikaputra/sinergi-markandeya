@extends('layouts.admin')

@section('title', 'Plotting Dosen Pembimbing Magang')

@section('content')
<div class="space-y-8">
    <!-- Assignment Form Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('assign.dosen.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Student Selection -->
                <div class="lg:col-span-7 space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Pilih Mahasiswa Magang</label>
                    <div class="bg-slate-50 rounded-3xl border border-gray-100 overflow-hidden">
                        <div class="max-h-[400px] overflow-y-auto sidebar-scroll p-4 space-y-2">
                            @foreach($mahasiswas as $mahasiswa)
                                <label class="flex items-center p-4 bg-white border border-gray-100 rounded-2xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="checkbox" name="nims[]" value="{{ $mahasiswa->nim }}" class="w-5 h-5 text-blue-600 border-gray-300 rounded-lg focus:ring-blue-500">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-sm font-bold text-gray-800 group-hover:text-blue-700 transition-colors">{{ $mahasiswa->nama }}</p>
                                        <div class="flex items-center space-x-2 mt-0.5">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $mahasiswa->nim }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-[10px] font-bold text-blue-500 uppercase tracking-tighter">
                                                Magang Program
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Dosen Selection & Submit -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Pilih Dosen Pembimbing</label>
                        <div class="relative">
                            <select name="nidn" required class="w-full pl-5 pr-10 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 font-bold focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 appearance-none transition-all">
                                <option value="">-- Pilih Nama Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->nidn }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                <i class="fas fa-user-tie text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-3 group">
                            <i class="fas fa-user-check group-hover:scale-110 transition-transform"></i>
                            <span class="uppercase tracking-widest text-xs">Simpan Plotting Magang</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Assignments List -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 flex items-center justify-between border-b border-gray-50">
            <h3 class="text-lg font-black text-gray-800 tracking-tight uppercase tracking-widest text-xs">Data Bimbingan Magang</h3>
        </div>
        <div class="overflow-x-auto p-8">
            <table class="w-full text-left border-separate border-spacing-0" id="assignmentsTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Mahasiswa</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Dosen Pembimbing</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($assignments as $assignment)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">
                                    {{ substr($assignment->mahasiswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $assignment->mahasiswa->nama }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $assignment->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-2 text-indigo-600">
                                <i class="fas fa-chalkboard-teacher text-xs"></i>
                                <span class="text-sm font-bold">{{ $assignment->dosen->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('assign.dosen.delete', $assignment->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100" onclick="return confirm('Hapus bimbingan ini?')">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#assignmentsTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari data..."
            }
        });
    });
</script>
@endsection
