import { createApp } from 'vue';
import CashFlowDashboard from './components/CashFlowDashboard.vue';

const el = document.getElementById('cashflow-dashboard');
if (el && window.__CASHFLOW_DATA__) {
  const data = window.__CASHFLOW_DATA__;
  createApp(CashFlowDashboard, {
    months: data.months || [],
    incomes: data.incomes || [],
    expenses: data.expenses || [],
    balances: data.balances || [],
    totalIncome: data.totalIncome ?? 0,
    totalExpense: data.totalExpense ?? 0,
    topCategories: data.topCategories || [],
  }).mount(el);
}
