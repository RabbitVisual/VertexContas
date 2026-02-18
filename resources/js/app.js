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
// Tour manager: startVertexTour/registerVertexTourSteps are set by tours.js when loaded (PanelUser/PanelAdmin layouts)
Alpine.data('tourManager', (userType = 'free') => ({
    userType: userType || 'free',
    activeTour: null,
    completedTours: [],
    startTour(tourId) {
        if (typeof window.startVertexTour === 'function') {
            window.startVertexTour(tourId, (id) => this.trackCompletion(id));
        }
    },
    trackCompletion(tourId) {
        if (!tourId) return;
        this.completedTours = [...(this.completedTours || []), tourId];
        if (this.$dispatch) this.$dispatch('tour-completed', { tourId });
    },
    hasCompleted(tourId) {
        return (this.completedTours || []).includes(tourId);
    },
}));
window.Alpine = Alpine;
// Notification system is handled by individual components

Alpine.start();
