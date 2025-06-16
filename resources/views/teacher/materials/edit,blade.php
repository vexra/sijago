<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Materi Pembelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('teacher.materials.update', $material) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="course_id" :value="__('Mata Pelajaran')" />
                            <select id="course_id" name="course_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $material->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Judul Materi')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $material->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="description" name="description" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $material->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        @if ($material->file_path)
                            <div class="mt-4">
                                <x-input-label :value="__('File Materi Saat Ini')" />
                                <p class="text-sm text-gray-600">
                                    <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File Saat Ini</a>
                                </p>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="remove_file" id="remove_file" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="remove_file" class="ml-2 text-sm text-gray-600">Hapus file ini</label>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4">
                            <x-input-label for="file" :value="__('Unggah File Materi Baru (Opsional)')" />
                            <input id="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" type="file" name="file">
                            <p class="mt-1 text-sm text-gray-500">PDF, DOCX, PPTX, Video, Gambar (Max 20MB). Akan mengganti file yang ada.</p>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="link" :value="__('Link Eksternal (Opsional)')" />
                            <x-text-input id="link" class="block mt-1 w-full" type="url" name="link" :value="old('link', $material->link)" placeholder="e.g., https://youtube.com/watch?v=xyz" />
                            <x-input-error :messages="$errors->get('link')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Jika ada file baru diunggah, link ini akan diabaikan.</p>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Materi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>