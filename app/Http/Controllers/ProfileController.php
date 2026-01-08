<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Cloudinary\Cloudinary;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir untuk mengedit profil pengguna.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui informasi profil pengguna di penyimpanan.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'identity_number' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'], // Maksimal 2MB
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->identity_number = $request->identity_number;

        // Upload ke Cloudinary menggunakan SDK langsung
        if ($request->hasFile('avatar')) {
            // Inisialisasi Cloudinary dengan CLOUDINARY_URL dari Heroku
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            
            // Upload file
            $result = $cloudinary->uploadApi()->upload(
                $request->file('avatar')->getRealPath(),
                ['folder' => 'avatars']
            );
            
            // Simpan URL aman ke database
            $user->avatar = $result['secure_url'];
        }

        // Menangani Pembaruan Password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
