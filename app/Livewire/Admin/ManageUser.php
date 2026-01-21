<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Locked;

class ManageUser extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Properti Form
    #[Locked]
    public $userId;
    public $name, $email, $role = 'nasabah', $password;

    // State Control
    public $isEdit = false;
    public $search = '';
    public $formKey; // Tambahkan properti ini

    public function mount()
    {
        $this->formKey = rand(); // Beri nilai awal
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,petugas,nasabah',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('message', 'User Berhasil Ditambahkan.');
        $this->resetInput();
    }

    public function edit($id)
    {
        $this->resetInput(); // Bersihkan sisa validasi/input sebelumnya
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEdit = true;
        $this->formKey = 'edit-' . $id;
        $this->dispatch('scroll-to-top');
    }

    public function update()
    {
        // 1. Validasi (Email unik kecuali untuk ID yang sedang diedit)
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required',
        ]);

        // 2. Cari user berdasarkan ID yang disimpan saat klik Edit
        $user = User::findOrFail($this->userId);

        // 3. Siapkan data
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        // 4. Update password hanya jika diisi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        // 5. Eksekusi Update
        $user->update($data);

        session()->flash('message', 'User Berhasil Diperbarui.');

        // 6. Bersihkan form
        $this->resetInput();
    }

    public function delete($id)
    {
        User::destroy($id);
        session()->flash('message', 'User Berhasil Dihapus.');
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('livewire.admin.manage-user', [
            'users' => $users
        ])->layout('layouts.app');
    }

    public function resetInput()
    {
        $this->reset(['name', 'email', 'password', 'userId', 'isEdit']);
        $this->role = 'nasabah';
        $this->resetValidation(); // Menghapus pesan error merah jika ada
        $this->formKey = rand();
    }
}
