import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';
import { initCashFlowChart, initSpendingChart } from './charts';

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;

// Expose chart functions globally
window.initCashFlowChart = initCashFlowChart;
window.initSpendingChart = initSpendingChart;

Alpine.start();
