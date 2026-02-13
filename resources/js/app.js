import './bootstrap';
import './theme';
import './auth-forms';
import './masks';
import './cep-lookup';

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
// Notification system is handled by individual components

Alpine.start();
