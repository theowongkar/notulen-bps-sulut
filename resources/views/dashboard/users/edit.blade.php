<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Edit Pengguna</x-slot>

    {{-- Bagian Tambah Pengguna --}}
    <section class="space-y-2">

        {{-- Flash Message --}}
        <div class="max-w-3xl mx-auto">
            <x-alerts.flash-message />
        </div>

        {{-- Form Tambah Pengguna --}}
        <div class="max-w-3xl mx-auto p-4 bg-white rounded-lg border border-gray-300 shadow">
            <h2 class="mb-5 font-semibold text-gray-700">Edit Pengguna</h2>

            <form action="{{ route('dashboard.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4">
                    <div x-data="{
                        preview: '',
                        placeholder: '{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/placeholder-profile.webp') }}'
                    }">
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-300">
                                <img :src="preview || placeholder" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <div class="relative">
                                <input type="file" name="avatar" id="avatar" accept="image/png,image/jpeg"
                                    class="absolute inset-0 opacity-0"
                                    @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''">

                                <button type="button"
                                    class="px-3 py-2 bg-indigo-50 text-indigo-700 text-sm rounded-md border border-gray-300 cursor-pointer">
                                    Pilih File
                                </button>
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Max 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <x-forms.input name="name" label="Nama" :value="old('name', $user->name)"></x-forms.input>
                    <x-forms.input name="email" label="Email" type="email" :value="old('email', $user->email)"></x-forms.input>
                    <x-forms.select name="role" label="Role Pengguna" :options="['Notulis', 'Admin']"
                        :selected="old('role', $user->role)"></x-forms.select>

                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full">
                            <x-forms.input name="password" label="Password" type="password"></x-forms.input>
                        </div>
                        <div class="w-full">
                            <x-forms.input name="password_confirmation" label="Konfirmasi Password"
                                type="password"></x-forms.input>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Kosongkan password jika tidak ingin mengganti.</p>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <x-buttons.primary-button href="{{ route('dashboard.user.index') }}"
                        class="bg-gray-600 hover:bg-gray-700">Kembali</x-buttons.primary-button>
                    <x-buttons.primary-button type="submit">Perbarui</x-buttons.primary-button>
                </div>
            </form>
        </div>
    </section>

</x-app-layout>
