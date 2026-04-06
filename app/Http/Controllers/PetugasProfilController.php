<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class PetugasProfilController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $data = [
            'content' => 'petugas.profil.edit',
            'user' => $user,
            'title' => 'Edit Profil'
        ];
        return view('petugas.layouts.wrapper', $data);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            // Handle upload foto profil
            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                    Storage::delete('public/' . $user->profile_picture);
                }
                $file = $request->file('profile_picture');
                $path = $file->store('profile_pictures', 'public');
                $validated['profile_picture'] = $path;
            }

            // Siapkan data untuk update
            $updateData = [];
            $updateData['name'] = $validated['name'];
            $updateData['email'] = $validated['email'];
            $updateData['phone'] = $validated['phone'] ?? null;
            $updateData['birth_date'] = $validated['birth_date'] ?? null;
            $updateData['address'] = $validated['address'] ?? null;

            if (isset($validated['profile_picture'])) {
                $updateData['profile_picture'] = $validated['profile_picture'];
            }

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            Alert::success('Sukses', 'Profil berhasil diperbarui');
            return redirect('/petugas/profil');

        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal memperbarui profil: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
