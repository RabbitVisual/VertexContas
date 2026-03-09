@extends('paneluser::wizard.layout')

@section('content')
<div class="w-full space-y-6 animate-in fade-in duration-500">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-2">Qual seu maior objetivo hoje?</h1>
        <p class="text-gray-600 dark:text-gray-400">Assim personalizamos sua experiência desde o início.</p>
    </div>

    <form action="{{ route('paneluser.wizard.purpose.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-4">
            @foreach(\Modules\PanelUser\Http\Controllers\WizardController::PURPOSE_OPTIONS as $slug => $label)
                <label class="flex items-center gap-4 p-5 rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-950 hover:border-primary-400 dark:hover:border-primary-500/50 cursor-pointer transition-all has-[:checked]:border-primary-500 has-[:checked]:ring-2 has-[:checked]:ring-primary-500/20">
                    <input type="radio" name="purpose" value="{{ $slug }}" {{ old('purpose') === $slug ? 'checked' : '' }} required class="w-5 h-5 text-primary-600 focus:ring-primary-500">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('purpose')
            <p class="text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
        @enderror
        @error('limit')
            <p class="text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
        @enderror
        <div class="flex justify-center pt-4">
            <button type="submit" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-bold text-lg shadow-lg shadow-primary-500/25 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Próximo
                <x-icon name="arrow-right" style="solid" class="w-5 h-5" />
            </button>
        </div>
    </form>
</div>
@endsection
