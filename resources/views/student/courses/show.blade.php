<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                    <p class="text-sm text-gray-500 mb-6">Guru Pengajar: {{ $course->teacher->name ?? 'Belum Ditentukan' }}</p>

                    <h3 class="text-lg font-medium text-gray-900 mb-3">Materi Pembelajaran</h3>
                    @forelse ($materials as $material)
                        <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <p class="font-semibold text-gray-800">{{ $material->title }}</p>
                            <p class="text-sm text-gray-600">{{ $material->description }}</p>
                            @if ($material->file_path)
                                <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm mt-1 block">Unduh File</a>
                            @endif
                            @if ($material->link)
                                <a href="{{ $material->link }}" target="_blank" class="text-blue-600 hover:underline text-sm mt-1 block">Akses Link</a>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">Diunggah: {{ $material->created_at->format('d M Y H:i') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600 mb-6">Belum ada materi untuk mata pelajaran ini.</p>
                    @endforelse

                    <h3 class="text-lg font-medium text-gray-900 mt-6 mb-3">Tugas</h3>
                    @forelse ($assignments as $assignment)
                        <div class="mb-4 p-4 border border-gray-200 rounded-lg @if($assignment->submissions->isNotEmpty() && $assignment->submissions->first()->grade !== null) bg-green-50 @elseif($assignment->submissions->isNotEmpty()) bg-blue-50 @else bg-red-50 @endif">
                            <p class="font-semibold text-gray-800">{{ $assignment->title }}</p>
                            <p class="text-sm text-gray-600">{{ $assignment->description }}</p>
                            <p class="text-xs text-gray-500">Batas Waktu: {{ $assignment->due_date->format('d M Y H:i') }}</p>
                            @if ($assignment->submissions->isNotEmpty())
                                <p class="text-sm mt-2">Status: <span class="font-semibold @if($assignment->submissions->first()->grade !== null) text-green-700 @else text-blue-700 @endif">Sudah Dikumpulkan</span></p>
                                @if($assignment->submissions->first()->grade !== null)
                                    <p class="text-sm">Nilai: <span class="font-bold text-lg text-green-800">{{ $assignment->submissions->first()->grade }}</span></p>
                                    @if($assignment->submissions->first()->feedback)
                                        <p class="text-sm">Umpan Balik: {{ $assignment->submissions->first()->feedback }}</p>
                                    @endif
                                @else
                                     <p class="text-sm">Nilai: <span class="font-semibold text-blue-700">Menunggu Penilaian</span></p>
                                @endif
                            @else
                                <p class="text-sm mt-2">Status: <span class="font-semibold text-red-700">Belum Dikumpulkan</span></p>
                            @endif
                            <a href="{{ route('student.assignments.show', $assignment) }}" class="inline-flex items-center px-3 py-1 mt-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Detail Tugas') }}
                            </a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">Belum ada tugas untuk mata pelajaran ini.</p>
                    @endforelse

                    {{-- Tambahkan bagian untuk Ujian/Kuis di sini jika sudah diimplementasikan --}}
                    <h3 class="text-lg font-medium text-gray-900 mt-6 mb-3">Ujian/Kuis (Coming Soon)</h3>
                    <p class="text-sm text-gray-600">Fitur ujian/kuis sedang dalam pengembangan.</p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>