<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Selamat Datang, {{ Auth::user()->name }} (Guru)!</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Di sini Anda dapat mengelola mata pelajaran, materi, dan tugas Anda.
                    </p>

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Perhatian!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-6 bg-purple-100 rounded-lg shadow">
                            <h4 class="text-xl font-semibold text-purple-800">Mata Pelajaran Anda</h4>
                            <ul class="mt-2 text-sm text-purple-700 list-disc list-inside">
                                @forelse ($courses as $course)
                                    <li>{{ $course->name }}</li>
                                @empty
                                    <li>Belum ada mata pelajaran yang ditugaskan.</li>
                                @endforelse
                            </ul>
                        </div>

                        <a href="{{ route('teacher.materials.index') }}" class="block p-6 bg-orange-100 rounded-lg shadow hover:bg-orange-200">
                            <h4 class="text-xl font-semibold text-orange-800">Manajemen Materi</h4>
                            <p class="mt-2 text-sm text-orange-700">Unggah dan kelola materi pembelajaran untuk mata pelajaran Anda.</p>
                        </a>

                        <a href="{{ route('teacher.assignments.index') }}" class="block p-6 bg-red-100 rounded-lg shadow hover:bg-red-200">
                            <h4 class="text-xl font-semibold text-red-800">Manajemen Tugas</h4>
                            <p class="mt-2 text-sm text-red-700">Buat, berikan, dan nilai tugas untuk siswa Anda.</p>
                        </a>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Materi Terbaru Anda</h4>
                        @forelse($recentMaterials as $material)
                            <div class="p-4 bg-gray-50 rounded-lg mb-2 shadow-sm">
                                <p class="font-semibold text-gray-800">{{ $material->title }}</p>
                                <p class="text-sm text-gray-600">Mata Pelajaran: {{ $material->course->name }}</p>
                                <p class="text-xs text-gray-500">Diunggah: {{ $material->created_at->format('d M Y') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">Belum ada materi yang diunggah.</p>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Tugas Mendatang</h4>
                        @forelse($upcomingAssignments as $assignment)
                            <div class="p-4 bg-gray-50 rounded-lg mb-2 shadow-sm">
                                <p class="font-semibold text-gray-800">{{ $assignment->title }}</p>
                                <p class="text-sm text-gray-600">Mata Pelajaran: {{ $assignment->course->name }}</p>
                                <p class="text-xs text-gray-500">Batas Waktu: {{ $assignment->due_date->format('d M Y H:i') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">Tidak ada tugas mendatang.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>