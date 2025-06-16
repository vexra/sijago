<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Akses materi, tugas, dan lihat perkembangan belajar Anda di sini.
                    </p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('student.courses.index') }}" class="block p-6 bg-blue-100 rounded-lg shadow hover:bg-blue-200">
                            <h4 class="text-xl font-semibold text-blue-800">Mata Pelajaran Saya</h4>
                            <p class="mt-2 text-sm text-blue-700">Lihat daftar mata pelajaran yang Anda ikuti.</p>
                        </a>

                        {{-- Untuk melihat semua tugas --}}
                        {{-- <a href="#" class="block p-6 bg-red-100 rounded-lg shadow hover:bg-red-200">
                            <h4 class="text-xl font-semibold text-red-800">Tugas Saya</h4>
                            <p class="mt-2 text-sm text-red-700">Lihat semua tugas dan statusnya.</p>
                        </a> --}}

                        {{-- Untuk melihat hasil ujian --}}
                        {{-- <a href="#" class="block p-6 bg-green-100 rounded-lg shadow hover:bg-green-200">
                            <h4 class="text-xl font-semibold text-green-800">Hasil Ujian</h4>
                            <p class="mt-2 text-sm text-green-700">Cek nilai ujian dan kuis Anda.</p>
                        </a> --}}
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Tugas Mendatang</h4>
                        @forelse($upcomingAssignments as $assignment)
                            <a href="{{ route('student.assignments.show', $assignment) }}" class="block p-4 bg-yellow-50 rounded-lg mb-2 shadow-sm hover:bg-yellow-100">
                                <p class="font-semibold text-yellow-800">{{ $assignment->title }}</p>
                                <p class="text-sm text-gray-600">Mata Pelajaran: {{ $assignment->course->name }}</p>
                                <p class="text-xs text-red-500">Batas Waktu: {{ $assignment->due_date->format('d M Y H:i') }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-gray-600">Tidak ada tugas mendatang.</p>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Materi Terbaru</h4>
                        @forelse($recentMaterials as $material)
                            <a href="{{ route('student.courses.show', $material->course) }}" class="block p-4 bg-gray-50 rounded-lg mb-2 shadow-sm hover:bg-gray-100">
                                <p class="font-semibold text-gray-800">{{ $material->title }}</p>
                                <p class="text-sm text-gray-600">Mata Pelajaran: {{ $material->course->name }}</p>
                                <p class="text-xs text-gray-500">Diunggah: {{ $material->created_at->format('d M Y') }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-gray-600">Belum ada materi terbaru.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>