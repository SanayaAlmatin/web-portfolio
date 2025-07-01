<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageProjects extends Component
{
    use WithFileUploads;

    // Properti untuk menampung semua project yang akan ditampilkan
    public $projects;

    // Properti untuk form, dihubungkan dengan wire:model di view
    public $projectId;

    public $title;

    public $description;

    public $image_path;

    public $project_url;

    public $existingImage;

    // Properti untuk mengontrol modal
    public $isModalOpen = false;

    /**
     * Fungsi yang dijalankan saat komponen pertama kali di-load.
     * Mirip seperti __construct().
     */
    public function mount()
    {
        $this->projects = Project::all();
    }

    /**
     * Fungsi ini wajib ada untuk me-render tampilan komponen.
     */
    public function render()
    {
        // Akan me-render file view di resources/views/livewire/admin/manage-projects.blade.php
        return view('livewire.admin.manage-projects');
    }

    /**
     * Membuka modal untuk membuat data baru.
     */
    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    /**
     * Membuka modal.
     */
    public function openModal()
    {
        $this->isModalOpen = true;
    }

    /**
     * Menutup modal.
     */
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    /**
     * Mereset semua properti form.
     */
    private function resetForm()
    {
        $this->projectId = null;
        $this->title = '';
        $this->description = '';
        $this->image_path = null;
        $this->project_url = '';
        $this->existingImage = null;
    }

    /**
     * Menyimpan data (baik membuat baru maupun mengupdate yang sudah ada).
     */
    public function store()
    {
        // Validasi input
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|max:2048',
            'project_url' => 'nullable|url',
        ]);

        // dd($this->image_path);

        $imagePath = $this->existingImage;
        if ($this->image_path) {
            if ($this->existingImage) {
                Storage::delete($this->existingImage);
            }

            $imagePath = $this->image_path->store('projects', 'public');
        }

        Project::updateOrCreate(
            ['id' => $this->projectId],
            [
                'title' => $this->title,
                'description' => $this->description,
                'image_path' => $imagePath, // Simpan path gambar
                'pubslish_at' => now(), // Atau bisa diisi dengan tanggal tertentu
                'project_url' => $this->project_url,
            ]
        );

        // Menampilkan pesan sukses
        session()->flash('message',
            $this->projectId ? 'Proyek berhasil diperbarui.' : 'Proyek berhasil dibuat.');

        // Menutup modal dan mereset form
        $this->closeModal();
        $this->resetForm();

        // Refresh daftar proyek
        $this->projects = Project::all();
    }

    /**
     * Mengisi form dengan data dari proyek yang akan di-edit.
     *
     * @param  int  $id  ID dari proyek
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $this->projectId = $id;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->project_url = $project->project_url;
        $this->existingImage = $project->image_path;
        $this->image_path = null;

        $this->openModal();
    }

    /**
     * Menghapus proyek dari database.
     *
     * @param  int  $id  ID dari proyek
     */
    public function delete($id)
    {
        $project = Project::findOrFail($id);

        // Hapus gambar terkait jika ada
        if ($project->image_path) {
            Storage::delete($project->image_path);
        }

        // Hapus proyek dari database
        $project->delete();

        // Menampilkan pesan sukses
        session()->flash('message', 'Proyek berhasil dihapus.');

        // Refresh daftar proyek
        $this->projects = Project::all();
    }
}
