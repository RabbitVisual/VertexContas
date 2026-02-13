/**
 * CEP Lookup Service
 * Future implementation for Brazilian zip code searches.
 */

export const lookupCEP = async (cep) => {
    const cleanCEP = cep.replace(/\D/g, '');
    if (cleanCEP.length !== 8) return null;

    try {
        const response = await fetch(`https://viacep.com.br/ws/${cleanCEP}/json/`);
        return await response.json();
    } catch (error) {
        console.error('CEP Lookup Error:', error);
        return null;
    }
};

window.lookupCEP = lookupCEP;
