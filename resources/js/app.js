import './bootstrap';
import './theme';
import './auth-forms';
import './masks';
import './cep-lookup';

import 'flowbite';

// Chart.js & ApexCharts: carregados via code-splitting só onde há gráficos (charts-chartjs.js / charts-apex.js)

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
// Notification system is handled by individual components

Alpine.start();
