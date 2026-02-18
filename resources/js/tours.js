/**
 * Vertex Contas - Guided Tours (Driver.js + Alpine.js)
 * Professional gamification: page tours, contextual help, navigation guidance.
 */
import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';

const POPOVER_CLASS = 'vertex-tour-popover bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200/50 dark:border-slate-700/50 shadow-xl rounded-xl';

function getDefaultConfig() {
    const isMobile = typeof window !== 'undefined' && window.innerWidth < 768;
    return {
        showProgress: true,
        showButtons: ['next', 'previous', 'close'],
        progressText: '{{current}} de {{total}}',
        doneBtnText: 'Finalizar',
        nextBtnText: 'Próximo',
        prevBtnText: 'Anterior',
        popoverClass: POPOVER_CLASS + (isMobile ? ' max-w-[calc(100vw-2rem)]' : ''),
        overlayOpacity: 0.7,
        stagePadding: isMobile ? 16 : 10,
        stageRadius: isMobile ? 12 : 8,
        allowClose: true,
        animate: true,
    };
}

const defaultConfig = getDefaultConfig();

let driverInstance = null;

function getDriver() {
    if (!driverInstance) {
        driverInstance = driver({
            ...defaultConfig,
            onDestroyed: () => {
                if (window.Alpine && window.Alpine.$data && typeof window.__vertexTourOnComplete === 'function') {
                    window.__vertexTourOnComplete(window.__vertexTourId);
                }
            },
        });
    }
    return driverInstance;
}

/**
 * Start a tour by id. Steps should be registered via registerTourSteps(tourId, steps).
 * @param {string} tourId
 * @param {Function} onComplete - optional callback when tour finishes
 */
export function startTour(tourId, onComplete) {
    window.__vertexTourId = tourId;
    window.__vertexTourOnComplete = onComplete || (() => {});
    const steps = window.__vertexTourSteps && window.__vertexTourSteps[tourId];
    if (!steps || !steps.length) {
        console.warn('[Vertex Tours] No steps registered for tour:', tourId);
        return;
    }
    const d = getDriver();
    d.setConfig({ ...getDefaultConfig(), steps });
    d.drive();
}

/**
 * Register steps for a tour. Call from page scripts or Alpine.
 * @param {string} tourId
 * @param {Array<{element: string, title: string, description: string, side?: string}>} steps
 */
export function registerTourSteps(tourId, steps) {
    if (!window.__vertexTourSteps) window.__vertexTourSteps = {};
    window.__vertexTourSteps[tourId] = steps.map((s) => ({
        element: s.element,
        popover: {
            title: s.title,
            description: s.description,
            side: s.side || 'bottom',
            showButtons: s.showButtons !== undefined ? s.showButtons : ['next', 'previous', 'close'],
            nextBtnText: s.nextBtnText || defaultConfig.nextBtnText,
            prevBtnText: s.prevBtnText || defaultConfig.prevBtnText,
            doneBtnText: s.doneBtnText || defaultConfig.doneBtnText,
        },
    }));
}

window.startVertexTour = startTour;
window.registerVertexTourSteps = registerTourSteps;
window.getVertexDriver = getDriver;

export default { startTour, registerTourSteps, getDriver };
