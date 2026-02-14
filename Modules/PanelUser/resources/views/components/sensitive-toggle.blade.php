{{-- Toggle global para esconder/exibir dados sensíveis. Proteção de privacidade em locais públicos. --}}
@php
    $storageKey = \App\Helpers\SensitiveHelper::STORAGE_KEY;
@endphp
<button type="button"
        id="sensitive-toggle"
        aria-label="Ocultar ou exibir valores e dados sensíveis"
        title="Ocultar valores (privacidade em locais públicos)"
        class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
    <span class="sensitive-show" aria-hidden="true">
        <x-icon name="eye" style="duotone" class="w-5 h-5" />
    </span>
    <span class="sensitive-hide hidden" aria-hidden="true">
        <x-icon name="eye-slash" style="duotone" class="w-5 h-5 text-amber-500" />
    </span>
</button>
<script>
(function() {
    var key = '{{ $storageKey }}';
    var btn = document.getElementById('sensitive-toggle');
    var showEl = btn && btn.querySelector('.sensitive-show');
    var hideEl = btn && btn.querySelector('.sensitive-hide');
    function apply() {
        var hidden = localStorage.getItem(key) === 'true';
        document.body.classList.toggle('sensitive-hidden', hidden);
        if (showEl && hideEl) {
            showEl.classList.toggle('hidden', hidden);
            hideEl.classList.toggle('hidden', !hidden);
        }
    }
    function toggle() {
        var hidden = localStorage.getItem(key) === 'true';
        localStorage.setItem(key, !hidden);
        apply();
    }
    if (btn) {
        btn.addEventListener('click', toggle);
    }
    apply();
})();
</script>
