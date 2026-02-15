<template>
  <div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
        <h3 class="font-bold text-slate-800 dark:text-white mb-4">Receitas vs Despesas</h3>
        <div ref="barChartRef" class="sensitive-value" style="min-height: 320px"></div>
      </div>
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
        <h3 class="font-bold text-slate-800 dark:text-white mb-4">Evolução do Saldo Acumulado</h3>
        <div ref="lineChartRef" class="sensitive-value" style="min-height: 320px"></div>
      </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
        <h3 class="font-bold text-slate-800 dark:text-white mb-4">Proporção no Período</h3>
        <div ref="donutChartRef" class="sensitive-value flex justify-center" style="min-height: 280px"></div>
      </div>
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
        <h3 class="font-bold text-slate-800 dark:text-white mb-4">Top Categorias de Despesa</h3>
        <div ref="categoryChartRef" class="sensitive-value" style="min-height: 280px"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import ApexCharts from 'apexcharts';

const props = defineProps({
  months: { type: Array, default: () => [] },
  incomes: { type: Array, default: () => [] },
  expenses: { type: Array, default: () => [] },
  balances: { type: Array, default: () => [] },
  totalIncome: { type: Number, default: 0 },
  totalExpense: { type: Number, default: 0 },
  topCategories: { type: Array, default: () => [] },
});

const barChartRef = ref(null);
const lineChartRef = ref(null);
const donutChartRef = ref(null);
const categoryChartRef = ref(null);
let barChart = null;
let lineChart = null;
let donutChart = null;
let categoryChart = null;

const fmt = (v) => 'R$ ' + (Number(v) || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 });

function renderCharts() {
  if (!window.ApexCharts) return;

  if (barChartRef.value && props.months?.length) {
    if (barChart) barChart.destroy();
    barChart = new ApexCharts(barChartRef.value, {
      series: [{ name: 'Receitas', data: props.incomes }, { name: 'Despesas', data: props.expenses }],
      chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'Poppins, sans-serif' },
      colors: ['#10b981', '#ef4444'],
      plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 5 } },
      dataLabels: { enabled: false },
      xaxis: { categories: props.months },
      yaxis: { labels: { formatter: fmt } },
      tooltip: { y: { formatter: fmt } },
      grid: { borderColor: '#f1f5f9' },
      legend: { position: 'top' },
    });
    barChart.render();
  }

  if (lineChartRef.value && props.months?.length) {
    if (lineChart) lineChart.destroy();
    lineChart = new ApexCharts(lineChartRef.value, {
      series: [{ name: 'Saldo Acumulado', data: props.balances }],
      chart: { type: 'line', height: 320, toolbar: { show: false }, fontFamily: 'Poppins, sans-serif' },
      colors: ['#0d9488'],
      stroke: { curve: 'smooth', width: 3 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
      dataLabels: { enabled: false },
      xaxis: { categories: props.months },
      yaxis: { labels: { formatter: fmt } },
      tooltip: { y: { formatter: fmt } },
      grid: { borderColor: '#f1f5f9' },
    });
    lineChart.render();
  }

  if (donutChartRef.value) {
    if (donutChart) donutChart.destroy();
    donutChart = new ApexCharts(donutChartRef.value, {
      series: [props.totalIncome, props.totalExpense],
      chart: { type: 'donut', height: 280, fontFamily: 'Poppins, sans-serif' },
      labels: ['Receitas', 'Despesas'],
      colors: ['#10b981', '#ef4444'],
      legend: { position: 'bottom' },
      plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: () => fmt(props.totalIncome - props.totalExpense) } } } } },
      dataLabels: { enabled: false },
    });
    donutChart.render();
  }

  if (categoryChartRef.value && props.topCategories?.length) {
    if (categoryChart) categoryChart.destroy();
    categoryChart = new ApexCharts(categoryChartRef.value, {
      series: [{ name: 'Despesas', data: props.topCategories.map((c) => c.total) }],
      chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'Poppins, sans-serif' },
      plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
      colors: props.topCategories.map((c) => c.color || '#64748b'),
      dataLabels: { enabled: false },
      xaxis: { categories: props.topCategories.map((c) => c.category), labels: { formatter: fmt } },
      grid: { borderColor: '#f1f5f9' },
    });
    categoryChart.render();
  }
}

onMounted(() => {
  renderCharts();
});

watch(
  () => [props.months, props.incomes, props.expenses, props.balances, props.totalIncome, props.totalExpense, props.topCategories],
  () => renderCharts(),
  { deep: true }
);
</script>
