const apiRoot = 'https://pokeapi.co/api/v2/pokemon/';
const maxConcurrentRequests = 4;

function isPlaceholder(image) {
    try {
        return new URL(image.src, window.location.href).pathname.endsWith('/placeholder.svg');
    } catch {
        return false;
    }
}

export async function fetchOfficialArtwork(slug) {
    const response = await fetch(`${apiRoot}${encodeURIComponent(slug)}`);
    if (!response.ok) {
        throw new Error(`PokéAPI respondió ${response.status} para ${slug}`);
    }

    const pokemon = await response.json();
    const artwork = pokemon.sprites?.other?.['official-artwork']?.front_default;
    if (typeof artwork !== 'string' || !artwork.startsWith('https://')) {
        throw new Error(`PokéAPI no devolvió official-artwork para ${slug}`);
    }

    return artwork;
}

export function pokemonNameToSlug(name) {
    return name
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/♀/g, '-f')
        .replace(/♂/g, '-m')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');
}

async function loadArtworkForElements(name, elements) {
    const slug = pokemonNameToSlug(name);
    if (!slug) return;

    try {
        const artwork = await fetchOfficialArtwork(slug);
        elements.forEach((element) => {
            if (element instanceof HTMLImageElement && isPlaceholder(element)) {
                element.src = artwork;
            }
            if (element instanceof HTMLButtonElement && element.hasAttribute('data-img')) {
                element.dataset.img = artwork;
            }
        });
    } catch (error) {
        console.warn(`No se pudo cargar la ilustración de ${name} desde PokéAPI.`, error);
    }
}

export async function loadMissingProductArtwork(root = document) {
    const elementsByName = new Map();

    root.querySelectorAll('[data-pokemon-name]').forEach((element) => {
        const name = element.dataset.pokemonName?.trim();
        if (!name) return;

        if (element instanceof HTMLImageElement && !isPlaceholder(element)) return;
        if (
            element instanceof HTMLButtonElement
            && element.querySelector('img')
            && !isPlaceholder(element.querySelector('img'))
        ) return;

        const elements = elementsByName.get(name) ?? [];
        elements.push(element);
        elementsByName.set(name, elements);
    });

    const jobs = Array.from(elementsByName, ([name, elements]) => [name, elements]);
    const workers = Array.from(
        { length: Math.min(maxConcurrentRequests, jobs.length) },
        async () => {
            while (jobs.length > 0) {
                const job = jobs.shift();
                if (job) await loadArtworkForElements(job[0], job[1]);
            }
        }
    );

    await Promise.all(workers);
}

export function initializeProductImageForm() {
    const form = document.getElementById('product-form');
    if (!form) return;

    const nameInput = form.querySelector('#nombrePro');
    const fileInput = form.querySelector('#productImage');
    const storedReference = form.querySelector('#urlFoto');
    const previewButton = form.querySelector('#previewBtn');
    const artworkButton = form.querySelector('#useArtworkBtn');
    const previewContainer = form.querySelector('#imagePreview');
    const previewImage = form.querySelector('#previewImg');
    const status = form.querySelector('#imageStatus');
    let referenceBeforeUpload = storedReference.value;
    let imageBeforeUpload = previewImage.src;
    let objectUrl = null;

    previewButton?.addEventListener('click', () => {
        previewContainer.style.display = 'block';
        previewContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    fileInput?.addEventListener('change', () => {
        const file = fileInput.files?.[0];
        if (!file) {
            storedReference.value = referenceBeforeUpload;
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
            previewImage.src = imageBeforeUpload;
            status.textContent = '';
            return;
        }

        referenceBeforeUpload = storedReference.value;
        imageBeforeUpload = previewImage.src;
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        objectUrl = URL.createObjectURL(file);
        storedReference.value = '';
        previewImage.src = objectUrl;
        previewContainer.style.display = 'block';
        status.textContent = `Vista previa de ${file.name}. Se guardará al actualizar el producto.`;
    });

    artworkButton?.addEventListener('click', async () => {
        const name = nameInput?.value.trim() ?? '';
        const slug = pokemonNameToSlug(name);
        if (!slug) {
            status.textContent = 'Escribe primero el nombre del Pokémon.';
            return;
        }

        artworkButton.disabled = true;
        status.textContent = 'Buscando ilustración oficial en PokéAPI...';
        try {
            const artwork = await fetchOfficialArtwork(slug);
            fileInput.value = '';
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
            storedReference.value = artwork;
            previewImage.src = artwork;
            imageBeforeUpload = artwork;
            referenceBeforeUpload = artwork;
            previewImage.dataset.pokemonName = name;
            previewContainer.style.display = 'block';
            status.textContent = 'Arte oficial seleccionado; se guardará al guardar el producto.';
        } catch (error) {
            console.warn(`No se pudo obtener el arte oficial de ${name} desde PokéAPI.`, error);
            status.textContent = 'No se encontró una ilustración oficial para ese nombre.';
        } finally {
            artworkButton.disabled = false;
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => loadMissingProductArtwork());
} else {
    loadMissingProductArtwork();
}
