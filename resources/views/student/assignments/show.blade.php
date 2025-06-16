<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tugas: ') . $assignment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold text-gray-900">{{ $assignment->title }}</h3>
                    <p class="text-sm text-gray-600 mb-4">Mata Pelajaran: {{ $assignment->course->name }}</p>
                    <p class="text-sm text-gray-700 mb-2">{{ $assignment->description }}</p>
                    <p class="text-sm text-gray-500 mb-4">Batas Waktu: <span class="font-semibold">{{ $assignment->due_date->format('d M Y H:i') }}</span></p>

                    @if ($assignment->file_path)
                        <div class="mb-4">
                            <p class="font-semibold text-gray-800">Lampiran Tugas:</p>
                            <a href="{{ Storage::url($assignment->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Unduh Lampiran</a>
                        </div>
                    @endif

                    <h4 class="text-lg font-semibold text-gray-900 mt-6 mb-3">Status Pengumpulan Anda</h4>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($submission)
                        <div class="p-4 border border-blue-200 rounded-lg bg-blue-50 mb-4">
                            <p class="font-semibold text-blue-800">Anda Sudah Mengumpulkan Tugas Ini.</p>
                            <p class="text-sm text-blue-700">Dikumpulkan pada: {{ $submission->created_at->format('d M Y H:i') }}</p>

                            @if ($submission->content)
                                <p class="mt-2 text-sm text-blue-700">Jawaban Teks: {{ $submission->content }}</p>
                            @endif
                            @if ($submission->file_path)
                                <p class="text-sm text-blue-700">File Jawaban: <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                            @endif

                            @if ($submission->grade !== null)
                                <p class="mt-2 text-lg font-bold text-green-800">Nilai Anda: {{ $submission->grade }}</p>
                                @if ($submission->feedback)
                                    <p class="text-sm text-green-700">Umpan Balik Guru: {{ $submission->feedback }}</p>
                                @endif
                            @else
                                <p class="mt-2 text-md font-semibold text-orange-700">Menunggu Penilaian Guru.</p>
                            @endif
                        </div>
                    @else
                        <div class="p-4 border border-red-200 rounded-lg bg-red-50 mb-4">
                            <p class="font-semibold text-red-800">Anda Belum Mengumpulkan Tugas Ini.</p>
                            @if (now()->isAfter($assignment->due_date))
                                <p class="text-sm text-red-700">Tugas ini sudah melewati batas waktu pengumpulan.</p>
                            @endif
                        </div>
                    @endif

                    @if (now()->isBefore($assignment->due_date) || ($submission && now()->isBefore($assignment->due_date)))
                        <h4 class="text-lg font-semibold text-gray-900 mt-6 mb-3">{{ $submission ? 'Edit Pengumpulan Anda' : 'Kumpulkan Tugas' }}</h4>
                        <form method="POST" action="{{ route('student.assignments.submit', $assignment) }}" enctype="multipart/form-data">
                            @csrf

                            <div>
                                <x-input-label for="content" :value="__('Jawaban Teks (Opsional)')" />
                                <textarea id="content" name="content" rows="5" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $submission->content ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="file" :value="__('Unggah File Jawaban (Opsional)')" />
                                <input id="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" type="file" name="file">
                                <p class="mt-1 text-sm text-gray-500">Max 20MB. Akan mengganti file yang ada jika Anda mengunggah baru.</p>
                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            </div>

                            @if ($submission && $submission->file_path)
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="remove_file" id="remove_file" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="remove_file" class="ml-2 text-sm text-gray-600">Hapus file yang sudah diunggah ini</label>
                                </div>
                            @endif


                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ms-4">
                                    {{ $submission ? __('Update Pengumpulan') : __('Kumpulkan Tugas') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @elseif (now()->isAfter($assignment->due_date) && !$submission)
                         <p class="text-md text-red-700 mt-4">Anda tidak dapat mengumpulkan tugas karena sudah melewati batas waktu.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>