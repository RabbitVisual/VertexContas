/**
 * Chart.js - Carregado sob demanda apenas no painel Admin.
 * Usado em: PanelAdmin index (revenueChart, userChart).
 */
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;
