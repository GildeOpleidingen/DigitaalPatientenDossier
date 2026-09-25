document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    const clientId = form.dataset.clientId 
        || new URLSearchParams(window.location.search).get('clientId') 
        || 'default';
    const pageName = window.location.pathname.split('/').pop().replace('.php', '') || 'form';
    const storageKey = `anamnese_draft_${clientId}_${pageName}`;

    function escapeSelector(name) {
        if (window.CSS && CSS.escape) {
            return CSS.escape(name);
        }
        return name.replace(/(["\\])/g, '\\$1');
    }

    function serializeFormData() {
        const data = {};
        const elements = form.elements;

        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            if (!el.name || el.disabled || el.type === 'submit' || el.type === 'button') {
                continue;
            }

            if (el.type === 'radio') {
                if (el.checked) {
                    data[el.name] = el.value;
                }
            } else if (el.type === 'checkbox') {
                data[el.name] = el.checked;
            } else {
                data[el.name] = el.value;
            }
        }
        return data;
    }

    function restoreFormData(data) {
        if (!data || typeof data !== 'object') return;

        for (const [name, val] of Object.entries(data)) {
            const escapedName = escapeSelector(name);

            const radios = form.querySelectorAll(`input[type="radio"][name="${escapedName}"]`);
            if (radios.length > 0) {
                radios.forEach(radio => {
                    radio.checked = (radio.value === String(val));
                });
                continue;
            }

            const checkbox = form.querySelector(`input[type="checkbox"][name="${escapedName}"]`);
            if (checkbox) {
                checkbox.checked = Boolean(val);
                continue;
            }

            const field = form.querySelector(`input[name="${escapedName}"], textarea[name="${escapedName}"], select[name="${escapedName}"]`);
            if (field) {
                field.value = val;
            }
        }
    }

    const savedDraft = localStorage.getItem(storageKey);
    if (savedDraft) {
        try {
            const parsedData = JSON.parse(savedDraft);
            restoreFormData(parsedData);
        } catch (error) {
            console.error('Error loading draft from localStorage:', error);
        }
    }

    let saveTimeout = null;
    function scheduleSave() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            const data = serializeFormData();
            localStorage.setItem(storageKey, JSON.stringify(data));
        }, 300);
    }

    form.addEventListener('input', scheduleSave);
    form.addEventListener('change', scheduleSave);

    form.addEventListener('submit', () => {
        localStorage.removeItem(storageKey);
    });
});
