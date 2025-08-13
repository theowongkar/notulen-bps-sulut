<x-app-layout>

    {{-- Script Tambahan --}}
    @push('scripts')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush

    {{-- Judul Halaman --}}
    <x-slot name="title">Edit Notulen</x-slot>

    <section class="space-y-2">
        <div class="max-w-3xl mx-auto">
            <x-alerts.flash-message />
        </div>

        <div class="max-w-3xl mx-auto p-4 bg-white rounded-lg border border-gray-300 shadow">
            <h2 class="mb-5 font-semibold text-gray-700">Edit Notulen</h2>

            <form action="{{ route('dashboard.minute.update', $minute->slug) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4">

                    {{-- Upload Evidence --}}
                    <div x-data="{
                        fileUrl: '{{ $minute->evidence ? asset('storage/' . $minute->evidence) : '' }}',
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            this.fileUrl = URL.createObjectURL(file);
                        },
                        removeFile() {
                            this.fileUrl = '';
                            $refs.fileInput.value = '';
                        }
                    }">
                        <label for="evidence" class="block mb-1 text-sm font-medium text-gray-700">Bukti
                            Tindaklanjut</label>
                        <div class="relative flex items-center justify-center border-2 border-dashed rounded-lg cursor-pointer bg-blue-50 hover:bg-blue-100 border-blue-300 overflow-hidden"
                            @click="$refs.fileInput.click()">
                            <input type="file" id="evidence" name="evidence" accept="image/png,image/jpeg"
                                class="hidden" x-ref="fileInput" @change="handleFile">
                            <div x-show="fileUrl" class="w-full max-h-56 overflow-auto bg-white">
                                <img :src="fileUrl" alt="Preview" class="w-full h-auto">
                            </div>
                            <div x-show="!fileUrl" class="flex flex-col items-center justify-center py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    class="mb-2 text-blue-500 w-6 h-6" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                                </svg>
                                <span class="text-sm text-blue-600 font-medium">Upload File</span>
                                <span class="text-xs text-gray-600">Format: JPG, JPEG, PNG. Max 2MB.</span>
                            </div>
                        </div>

                        {{-- Tombol Hapus --}}
                        <div x-show="fileUrl" class="flex justify-center mt-2">
                            <button @click="removeFile" type="button"
                                class="px-2 py-1 bg-red-600 text-white text-sm rounded-md cursor-pointer hover:bg-red-700">
                                Hapus
                            </button>
                        </div>

                        @error('evidence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Problem --}}
                    <div>
                        <label for="problem" class="block mb-1 text-sm font-medium text-gray-700">Masalah</label>
                        <input id="problem" value="{{ old('problem', $minute->problem) }}" type="hidden"
                            name="problem">
                        <trix-editor input="problem"></trix-editor>
                        @error('problem')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Solution --}}
                    <div>
                        <label for="solution" class="block mb-1 text-sm font-medium text-gray-700">Solusi</label>
                        <input id="solution" value="{{ old('solution', $minute->solution) }}" type="hidden"
                            name="solution">
                        <trix-editor input="solution"></trix-editor>
                        @error('solution')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Follow Up Plan --}}
                    <div>
                        <label for="follow_up_plan" class="block mb-1 text-sm font-medium text-gray-700">Rencana
                            Tindaklanjut</label>
                        <input id="follow_up_plan" value="{{ old('follow_up_plan', $minute->follow_up_plan) }}"
                            type="hidden" name="follow_up_plan">
                        <trix-editor input="follow_up_plan"></trix-editor>
                        @error('follow_up_plan')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Data Source --}}
                    <div>
                        <label for="data_source" class="block mb-1 text-sm font-medium text-gray-700">Sumber
                            Data</label>
                        <input id="data_source" value="{{ old('data_source', $minute->data_source) }}" type="hidden"
                            name="data_source">
                        <trix-editor input="data_source"></trix-editor>
                        @error('data_source')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full">
                            <x-forms.select name="followed_up_by" label="Ditindaklanjuti oleh" :options="$users->pluck('name', 'id')"
                                :selected="old('followed_up_by', $minute->followed_up_by)" />
                        </div>
                        <div class="w-full">
                            <x-forms.input name="follow_up_limits" label="Batas Tindak Lanjut" type="date"
                                value="{{ old('follow_up_limits', \Carbon\Carbon::parse($minute->follow_up_limits)->format('Y-m-d')) }}" />
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="mt-4 flex justify-end gap-2">
                    <x-buttons.primary-button href="{{ route('dashboard.minute.index') }}"
                        class="bg-gray-600 hover:bg-gray-700">Kembali</x-buttons.primary-button>
                    <x-buttons.primary-button type="submit">Update</x-buttons.primary-button>
                </div>
            </form>
        </div>
    </section>

</x-app-layout>
