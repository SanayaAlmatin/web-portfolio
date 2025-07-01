<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Manajemen Sertifikat</h2>

    <button
        wire:click="create()"
        class="bg-blue-500 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded mb-4"
    >
        Tambah Sertifikat Baru
    </button>

    @if (session()->has('message'))
        <div
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
            role="alert"
        >
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if ($isModalOpen)
        <div
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
        >
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
                <h3 class="text-xl font-semibold mb-4">
                    {{ $certificateId ? 'Edit Sertifikat' : 'Tambah Sertifikat Baru' }}
                </h3>

                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label
                            for="title"
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            Nama Sertifikat:
                        </label>
                        <input
                            type="text"
                            id="title"
                            wire:model.lazy="title"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        />
                        @error('title')
                            <span class="text-red-500 text-xs">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label
                            for="description"
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            Deskripsi:
                        </label>
                        <textarea
                            id="description"
                            wire:model.lazy="description"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            rows="4"
                        ></textarea>
                        @error('description')
                            <span class="text-red-500 text-xs">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label
                            for="image_path"
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            Gambar (Opsional):
                        </label>
                        <input
                            type="file"
                            id="image_path"
                            wire:model="image_path"
                            accept="image/*"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        />
                        <div
                            wire:loading
                            wire:target="image_path"
                            class="text-sm text-gray-500 mt-1"
                        >
                            Mengunggah...
                        </div>
                        @error('image_path')
                            <span class="text-red-500 text-xs">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="button"
                            wire:click="closeModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                    >
                        Nama Sertifikat
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                    >
                        Deskripsi
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                    >
                        Gambar
                    </th>
                    <th
                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                    >
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($certificates as $certificate)
                    <tr>
                        <td
                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $certificate->title }}
                            </p>
                        </td>
                        <td
                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $certificate->description }}
                            </p>
                        </td>
                        <td
                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        >
                            @if ($certificate->image_path)
                                <img
                                    src="{{ asset('storage/' . $certificate->image_path) }}"
                                    alt="{{ $certificate->title }}"
                                    class="w-16 h-auto"
                                />
                            @else
                                    Tidak ada gambar
                            @endif
                        </td>
                        <td
                            class="px-5 py-5 border-b border-gray-200 bg-white text-sm"
                        >
                            <button
                                wire:click="edit({{ $certificate->id }})"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded mr-2"
                            >
                                Edit
                            </button>
                            <button
                                wire:click="delete({{ $certificate->id }})"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
