{{-- Toggle global para esconder/exibir dados sensíveis. Proteção de privacidade em locais públicos. Suporta múltiplas instâncias (desktop + mobile). --}}
@php
    $storageKey = \App\Helpers\SensitiveHelper::STORAGE_KEY;
@endphp
<button type="button"
        class="js-sensitive-toggle inline-flex items-center justify-center p-2.5 rounded-xl text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        aria-label="Ocultar ou exibir valores e dados sensíveis"
        title="Ocultar valores (privacidade em locais públicos)">
    <span class="sensitive-show" aria-hidden="true">
        <x-icon name="eye" style="duotone" class="w-5 h-5" />
    </span>
    <span class="sensitive-hide hidden" aria-hidden="true">
        <x-icon name="eye-slash" style="duotone" class="w-5 h-5 text-amber-500" />
    </span>
</button>
@once
<script>
(function() {
    if (window.__sensitiveToggleInit) return;
    window.__sensitiveToggleInit = true;
    var key = '{{ $storageKey }}';
    function apply() {
        var hidden = localStorage.getItem(key) === 'true';
        document.body.classList.toggle('sensitive-hidden', hidden);
        document.querySelectorAll('.js-sensitive-toggle .sensitive-show').forEach(function(el){ el.classList.toggle('hidden', hidden); });
        document.querySelectorAll('.js-sensitive-toggle .sensitive-hide').forEach(function(el){ el.classList.toggle('hidden', !hidden); });
    }
    function toggle() {
        localStorage.setItem(key, localStorage.getItem(key) !== 'true');
        apply();
    }
    function init() {
        document.querySelectorAll('.js-sensitive-toggle').forEach(function(btn){ btn.addEventListener('click', toggle); });
        apply();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
</script>
@endonce
