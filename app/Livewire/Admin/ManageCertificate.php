<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ManageCertificate extends Component
{
    use \Livewire\WithFileUploads;

    public $certificates;

    public $certificateId;

    public $title;

    public $description;

    public $image_path; // Untuk menyimpan path gambar

    public $published_at; // Untuk menyimpan tanggal publikasi

    public $existingImage; // Untuk menyimpan gambar yang sudah ada

    public $isModalOpen = false;

    public function mount()
    {
        // Ambil semua data sertifikat dari database
        $this->certificates = \App\Models\Certificate::all();
    }

    public function render()
    {
        return view('livewire.admin.manage-certificate');
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetForm()
    {
        $this->certificateId = null;
        $this->title = '';
        $this->description = '';
        $this->image_path = null; // Reset gambar
        $this->published_at = null; // Reset tanggal publikasi
        $this->existingImage = null; // Reset gambar yang sudah ada
    }

    public function store()
    {
        // Validasi input
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|max:2048',
        ]);

        // dd($this->image_path);

        $imagePath = $this->existingImage;
        if ($this->image_path) {
            if ($this->existingImage) {
                Storage::delete($this->existingImage);
            }

            $imagePath = $this->image_path->store('certificates', 'public');
        }

        Certificate::updateOrCreate(
            ['id' => $this->certificateId],
            [
                'title' => $this->title,
                'description' => $this->description,
                'image_path' => $imagePath, // Simpan path gambar
                'pubslish_at' => now(), // Atau bisa diisi dengan tanggal tertentu
            ]
        );

        // Menampilkan pesan sukses
        session()->flash('message',
            $this->certificateId ? 'Sertifikat berhasil diperbarui.' : 'Proyek berhasil dibuat.');

        // Menutup modal dan mereset form
        $this->closeModal();
        $this->resetForm();

        // Refresh daftar proyek
        $this->certificates = Certificate::all();
    }

    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);
        $this->certificateId = $certificate->id;
        $this->title = $certificate->title;
        $this->description = $certificate->description;
        $this->image_path = null; // Simpan path gambar
        $this->published_at = $certificate->published_at; // Simpan tanggal publikasi
        $this->existingImage = $certificate->image_path; // Simpan gambar yang sudah ada

        $this->openModal();
    }

    public function delete($id)
    {
        $certificate = Certificate::findOrFail($id);
        if ($certificate->image_path) {
            Storage::delete($certificate->image_path);
        }
        $certificate->delete();

        session()->flash('message', 'Sertifikat berhasil dihapus.');

        // Refresh daftar sertifikat
        $this->certificates = Certificate::all();
    }
}
