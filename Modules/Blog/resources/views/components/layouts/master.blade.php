@props(['title' => 'Vertex Insights', 'description' => '', 'image' => ''])

<x-paneluser::layouts.master :title="$title">
    @push('styles')
        <style>
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.5);
            }
            .dark .glass-card {
                background: rgba(30, 41, 59, 0.6);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .glass-hero {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.18);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            }
        </style>
        @if($description)
            <meta name="description" content="{{ $description }}">
            <meta property="og:description" content="{{ $description }}">
        @endif
        @if($image)
            <meta property="og:image" content="{{ asset($image) }}">
            <meta name="twitter:image" content="{{ asset($image) }}">
        @endif
    @endpush

    {{ $slot }}

    @push('scripts')

    @endpush
</x-paneluser::layouts.master>
