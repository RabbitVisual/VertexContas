import IMask from 'imask';

/**
 * Global Mask Configuration (Brazil Standard - Reinan Rodrigues Pack)
 * Provides comprehensive support for Brazilian formats: CPF, CNPJ, Phone, CEP, Currency, etc.
 */
const masks = {
    cpf: { mask: '000.000.000-00' },
    cnpj: { mask: '00.000.000/0000-00' },
    cep: { mask: '00000-000' },

    // Dynamic Phone Mask (Landline 8 digits / Mobile 9 digits)
    phone: {
        mask: [
            { mask: '(00) 0000-0000' },
            { mask: '(00) 00000-0000' }
        ]
    },

    // Date (DD/MM/YYYY)
    date: {
        mask: Date,
        pattern: 'd{/}`m{/}`Y',
        blocks: {
            d: {
                mask: IMask.MaskedRange,
                from: 1,
                to: 31,
                maxLength: 2,
            },
            m: {
                mask: IMask.MaskedRange,
                from: 1,
                to: 12,
                maxLength: 2,
            },
            Y: {
                mask: IMask.MaskedRange,
                from: 1900,
                to: 9999,
            }
        },
        format: date => {
            let day = date.getDate();
            let month = date.getMonth() + 1;
            const year = date.getFullYear();
            if (day < 10) day = "0" + day;
            if (month < 10) month = "0" + month;
            return [day, month, year].join('/');
        },
        parse: str => {
            const yearMonthDay = str.split('/');
            return new Date(yearMonthDay[2], yearMonthDay[1] - 1, yearMonthDay[0]);
        },
        autofix: true, // Smart autofix for dates
        lazy: false    // Show placeholder immediately
    },

    // Currency (BRL)
    money: {
        mask: 'R$ num',
        blocks: {
            num: {
                mask: Number,
                thousandsSeparator: '.',
                radix: ',',  // Decimal separator
                scale: 2,    // Digits after point
                signed: false,  // Disallow negative
                normalizeZeros: true,  // Append 00 if missing
                padFractionalZeros: true,  // Pad with 0s
            }
        }
    },

    // Percentage
    percent: {
        mask: 'num%',
        blocks: {
            num: {
                mask: Number,
                scale: 2,
                radix: ',',
                mapToRadix: ['.'],
                min: 0,
                max: 100
            }
        }
    },

    // Credit Card (Generic)
    card: {
        mask: '0000 0000 0000 0000'
    }
};

/**
 * Initialize Alpine.js Directive: x-mask
 * Usage: <input x-mask="'cpf'" x-model="user.cpf">
 */
document.addEventListener('alpine:init', () => {
    window.Alpine.directive('mask', (el, { expression }, { evaluate, effect }) => {
        const configName = evaluate(expression);
        const options = typeof configName === 'string' ? (masks[configName] || { mask: configName }) : configName;

        if (!options) {
            console.warn(`[Alpine Mask] Mask definition '${expression}' not found.`);
            return;
        }

        const maskInstance = IMask(el, options);

        // Two-way binding for x-model (update Alpine data when mask changes)
        maskInstance.on('accept', () => {
            el.dispatchEvent(new CustomEvent('input', { bubbles: true }));
        });

        // Cleanup on element removal
        effect(() => {
            return () => maskInstance.destroy();
        });
    });
});

export default masks;
