<?php

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        return view('paneluser::profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Show the user profile.
     */
    public function show()
    {
        return view('paneluser::profile.show', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the profile data.
     */
    /**
     * Update the profile data.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Base Validation Rules
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
        ];

        // 2. CPF Security Logic
        // Only allow CPF update if it is currently null/empty
        if (empty($user->cpf)) {
            $rules['cpf'] = ['nullable', 'string', 'size:14', Rule::unique('users')->ignore($user->id)];
        }

        $validated = $request->validate($rules);

        // 3. Security Enforcement: Ensure CPF is not updated if already exists
        if (!empty($user->cpf)) {
            unset($validated['cpf']);
        }

        $user->fill($validated);

        // Check if email changed to re-verify (optional, but good practice)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
    /**
     * Upload new profile photos.
     */
    public function uploadPhoto(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'photos' => ['required', 'array', 'max:3'],
            'photos.*' => ['image', 'max:2048'], // 2MB Max per file
        ]);

        $currentCount = $user->photos()->count();
        $newCount = count($request->file('photos'));

        if (($currentCount + $newCount) > 3) {
             return back()->withErrors(['photos' => "Você só pode ter até 3 fotos. Você já tem {$currentCount} e tentou enviar {$newCount}."]);
        }

        foreach ($request->file('photos') as $photoFile) {
            $path = $photoFile->store('profile-photos', 'public');
            $user->photos()->create(['path' => $path]);

            // Set first uploaded photo as active if user has none
            if (!$user->photo) {
                $user->update(['photo' => $path]);
            }
        }

        return back()->with('success', 'Fotos enviadas com sucesso!');
    }

    /**
     * Set a photo as active profile picture.
     */
    public function setProfilePhoto($id)
    {
        $user = auth()->user();
        $photo = $user->photos()->findOrFail($id);

        $user->update(['photo' => $photo->path]);

        return back()->with('success', 'Foto de perfil atualizada!');
    }

    /**
     * Delete a profile photo.
     */
    public function deletePhoto($id)
    {
        $user = auth()->user();
        $photo = $user->photos()->findOrFail($id);

        // Delete from storage
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo->path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->path);
        }

        // If deleting active photo, unset it or set another
        if ($user->photo === $photo->path) {
            $nextPhoto = $user->photos()->where('id', '!=', $id)->first();
            $user->update(['photo' => $nextPhoto ? $nextPhoto->path : null]);
        }

        $photo->delete();

        return back()->with('success', 'Foto removida com sucesso!');
    }
}
