<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mata Pelajaran Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Mata Pelajaran yang Anda Ikuti</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($enrolledCourses as $course)
                            <a href="{{ route('student.courses.show', $course) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50">
                                <h4 class="text-xl font-semibold text-gray-900">{{ $course->name }}</h4>
                                <p class="mt-2 text-sm text-gray-600">{{ Str::limit($course->description, 100) }}</p>
                                <p class="mt-2 text-xs text-gray-500">Guru: {{ $course->teacher->name ?? 'Belum Ditentukan' }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-gray-600">Anda belum terdaftar di mata pelajaran apapun. Silakan hubungi admin atau guru Anda.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $enrolledCourses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>