<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminProfileController extends Controller
{
    /**
     * Display the admin profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('paneladmin::profile.show', compact('user'));
    }

    /**
     * Show the form for editing the admin profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('paneladmin::profile.edit', compact('user'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date_format:d/m/Y',
            'show_assistant' => 'nullable|boolean',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $birthDate = null;
        if ($request->filled('birth_date')) {
            $parsed = \Carbon\Carbon::createFromFormat('d/m/Y', $request->birth_date);
            $birthDate = $parsed->format('Y-m-d');
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => lgpd_clean_phone($request->phone) ?: null,
            'cpf' => lgpd_clean_cpf($request->cpf ?? null) ?: null,
            'birth_date' => $birthDate,
            'show_assistant' => $request->has('show_assistant') ? $request->boolean('show_assistant') : ($user->show_assistant ?? true),
        ]);

        if ($request->filled('new_password')) {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Update the admin profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('users', 'public');
            $user->update(['photo' => $path]);

            return back()->with('success', 'Foto do perfil atualizada com sucesso!');
        }

        return back()->with('error', 'Erro ao carregar a imagem.');
    }
}
