<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Selamat Datang, {{ Auth::user()->name }} (Admin)!</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Di sini Anda dapat mengelola pengguna, mata pelajaran, dan pengaturan sistem lainnya.
                    </p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('admin.users.index') }}" class="block p-6 bg-blue-100 rounded-lg shadow hover:bg-blue-200">
                            <h4 class="text-xl font-semibold text-blue-800">Manajemen Pengguna</h4>
                            <p class="mt-2 text-sm text-blue-700">Lihat, tambah, edit, atau hapus akun pengguna (siswa, guru, admin).</p>
                        </a>

                        <a href="{{ route('admin.courses.index') }}" class="block p-6 bg-green-100 rounded-lg shadow hover:bg-green-200">
                            <h4 class="text-xl font-semibold text-green-800">Manajemen Mata Pelajaran</h4>
                            <p class="mt-2 text-sm text-green-700">Buat, edit, atau hapus mata pelajaran dan tetapkan guru pengajar.</p>
                        </a>

                        {{-- Tambahkan link fitur lain di sini, misal: --}}
                        {{-- <div class="block p-6 bg-yellow-100 rounded-lg shadow">
                            <h4 class="text-xl font-semibold text-yellow-800">Manajemen Pengumuman</h4>
                            <p class="mt-2 text-sm text-yellow-700">Buat dan kelola pengumuman untuk seluruh pengguna.</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>