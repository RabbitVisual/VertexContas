import ApexCharts from 'apexcharts';

// Make ApexCharts available globally
window.ApexCharts = ApexCharts;

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Cash Flow Chart
    const cashFlowElement = document.querySelector("#cashFlowChart");
    if (cashFlowElement && typeof cashFlowData !== 'undefined') {
        const cashFlowOptions = {
            series: [{
                name: 'Receitas',
                data: cashFlowData.income || [0, 0, 0, 0, 0, 0]
            }, {
                name: 'Despesas',
                data: cashFlowData.expenses || [0, 0, 0, 0, 0, 0]
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: 'Poppins, sans-serif'
            },
            colors: ['#10b981', '#ef4444'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.6,
                    opacityTo: 0.1,
                }
            },
            xaxis: {
                categories: cashFlowData.months || ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    },
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '14px',
                fontWeight: 700,
                labels: {
                    colors: '#64748b'
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                    }
                }
            }
        };

        const cashFlowChart = new ApexCharts(cashFlowElement, cashFlowOptions);
        cashFlowChart.render();
    }

    // Category Spending Chart
    const categoryElement = document.querySelector("#categoryChart");
    if (categoryElement && typeof categoryData !== 'undefined') {
        const categoryOptions = {
            series: categoryData.values || [0],
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'Poppins, sans-serif'
            },
            labels: categoryData.labels || ['Sem dados'],
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'],
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + '%';
                },
                style: {
                    fontSize: '14px',
                    fontWeight: 700
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '14px',
                fontWeight: 600,
                labels: {
                    colors: '#64748b'
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                fontSize: '16px',
                                fontWeight: 700
                            },
                            value: {
                                fontSize: '24px',
                                fontWeight: 900,
                                formatter: function (val) {
                                    return 'R$ ' + parseFloat(val).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Gasto',
                                fontSize: '14px',
                                fontWeight: 700,
                                formatter: function (w) {
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return 'R$ ' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                    }
                }
            }
        };

        const categoryChart = new ApexCharts(categoryElement, categoryOptions);
        categoryChart.render();
    }
});
