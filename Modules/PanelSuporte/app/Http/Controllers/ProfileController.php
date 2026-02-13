<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        return view('panelsuporte::profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Show the user profile.
     */
    public function show()
    {
        return view('panelsuporte::profile.show', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the profile data.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Base Validation Rules
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        // 2. CPF Security Logic: Only Admin can edit CPF
        if ($user->hasRole('admin')) {
            $rules['cpf'] = ['nullable', 'string', 'size:14', Rule::unique('users')->ignore($user->id)];
        }

        $validated = $request->validate($rules);

        // 3. Password handling
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // 4. Security Enforcement: Ensure CPF is not updated if not admin
        if (!$user->hasRole('admin')) {
            unset($validated['cpf']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Handle profile photo uploads.
     */
    public function uploadPhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photos' => ['required', 'array', 'max:3'],
            'photos.*' => ['image', 'max:2048'],
        ]);

        $currentCount = $user->photos()->count();
        $newCount = count($request->file('photos'));

        if (($currentCount + $newCount) > 3) {
             return back()->withErrors(['photos' => "Você só pode ter até 3 fotos. Você já tem {$currentCount}."]);
        }

        foreach ($request->file('photos') as $photoFile) {
            $path = $photoFile->store('profile-photos', 'public');
            $user->photos()->create(['path' => $path]);

            if (!$user->photo) {
                $user->update(['photo' => $path]);
            }
        }

        return back()->with('success', 'Fotos enviadas com sucesso!');
    }

    public function setProfilePhoto($id)
    {
        $user = Auth::user();
        $photo = $user->photos()->findOrFail($id);
        $user->update(['photo' => $photo->path]);
        return back()->with('success', 'Foto de perfil atualizada!');
    }

    public function deletePhoto($id)
    {
        $user = Auth::user();
        $photo = $user->photos()->findOrFail($id);

        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        if ($user->photo === $photo->path) {
            $nextPhoto = $user->photos()->where('id', '!=', $id)->first();
            $user->update(['photo' => $nextPhoto ? $nextPhoto->path : null]);
        }

        $photo->delete();

        return back()->with('success', 'Foto removida!');
    }
}
