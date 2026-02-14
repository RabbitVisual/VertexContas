export function initCashFlowChart(selector, labels, incomeData, expenseData, isDark = false) {
    const options = {
        series: [
            { name: 'Receitas', data: incomeData },
            { name: 'Despesas', data: expenseData }
        ],
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'Poppins, Inter, ui-sans-serif, system-ui, sans-serif',
            toolbar: { show: false },
            animations: { enabled: true, easing: 'easeinout', speed: 800 },
            background: 'transparent'
        },
        colors: ['#10b981', '#f43f5e'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: labels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: '#94a3b8', fontSize: '12px' }
            }
        },
        yaxis: {
            labels: {
                style: { colors: '#94a3b8', fontSize: '12px' },
                formatter: (val) => `R$ ${val}`
            }
        },
        grid: {
            borderColor: isDark ? '#334155' : '#e2e8f0',
            strokeDashArray: 4,
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: { formatter: (val) => `R$ ${val.toFixed(2)}` }
        },
        legend: { position: 'top', horizontalAlign: 'right' }
    };

    const chart = new ApexCharts(document.querySelector(selector), options);
    chart.render();
    return chart;
}

export function initSpendingChart(selector, seriesData, labels, colors, isDark = false) {
    const options = {
        series: seriesData,
        chart: {
            type: 'donut',
            height: 280,
            fontFamily: 'Poppins, Inter, ui-sans-serif, system-ui, sans-serif',
            background: 'transparent'
        },
        labels: labels,
        colors: colors,
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: { show: true, fontSize: '14px', fontFamily: 'Inter', color: '#64748b' },
                        value: {
                            show: true,
                            fontSize: '16px',
                            fontFamily: 'Inter',
                            fontWeight: 600,
                            color: isDark ? '#fff' : '#1e293b',
                            formatter: (val) => `R$ ${parseFloat(val).toFixed(2)}`
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            color: '#64748b',
                            formatter: function (w) {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return `R$ ${total.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: false },
        legend: { position: 'bottom' },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: { formatter: (val) => `R$ ${val.toFixed(2)}` }
        }
    };

    const chart = new ApexCharts(document.querySelector(selector), options);
    chart.render();
    return chart;
}
