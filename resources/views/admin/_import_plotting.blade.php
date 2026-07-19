{{-- Import CSV Plotting: $importRoute, $label, $formatInfo, $color --}}
<div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h5 class="text-sm font-bold text-gray-800 flex items-center mb-1">
                <i class="fas fa-file-csv text-{{ $color ?? 'blue' }}-500 mr-2"></i> Import {{ $label }} via CSV
            </h5>
            <p class="text-xs text-gray-400">Format: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 font-mono">{{ $formatInfo }}</code></p>
        </div>
        <form action="{{ $importRoute }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="file_csv" accept=".csv,.txt" required class="block text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-{{ $color ?? 'blue' }}-50 file:text-{{ $color ?? 'blue' }}-600 cursor-pointer hover:file:bg-{{ $color ?? 'blue' }}-100">
            <button type="submit" class="px-5 py-2.5 bg-{{ $color ?? 'blue' }}-600 hover:bg-{{ $color ?? 'blue' }}-700 text-white rounded-xl text-xs font-black transition-all whitespace-nowrap shadow-sm">
                <i class="fas fa-upload mr-1"></i> Upload
            </button>
        </form>
    </div>
</div>
