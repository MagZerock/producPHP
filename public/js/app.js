function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    return text.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}


async function loadProducts() {
    const dbErrorDiv  = document.getElementById('db-error');
    const dbErrorText = document.getElementById('db-error-text');

    try {
        const response = await fetch('index.php?action=list');
        const result   = await response.json();

        dbErrorDiv.classList.add('is-hidden');
        dbErrorText.innerText    = '';

        if (result.success) {
            const tbody = document.getElementById('products-tbody');
            tbody.innerHTML = '';

            if (result.products.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="loading-message">
                            No products found in the database.
                        </td>
                    </tr>`;
            } else {
                result.products.forEach(prod => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="table-id">${escapeHtml(prod.id)}</td>
                        <td>${escapeHtml(prod.name)}</td>
                        <td>${escapeHtml(prod.brand)}</td>
                        <td>${escapeHtml(prod.flavor)}</td>
                        <td>${escapeHtml(prod.category)}</td>
                        <td>$ ${Number(prod.price).toFixed(2)}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            const totalTr = document.createElement('tr');
            totalTr.className = 'total-row';
            totalTr.innerHTML = `
                <td colspan="5" class="table-total-label">Total Prices Sum:</td>
                <td class="table-total-value">$ ${Number(result.totalPrice).toFixed(2)}</td>
            `;
            tbody.appendChild(totalTr);
        } else {
            dbErrorText.innerText    = result.error || 'Failed to retrieve products.';
            dbErrorDiv.classList.remove('is-hidden');
        }
    } catch (err) {
        dbErrorText.innerText    = 'Network error fetching products: ' + err.message;
        dbErrorDiv.classList.remove('is-hidden');
    }
}


function initAddProductForm() {
    const form = document.getElementById('product-form');
    if (!form) return;

    const fields = [
        document.getElementById('name'),
        document.getElementById('brand'),
        document.getElementById('category'),
        document.getElementById('price')
    ].filter(Boolean);

    const validationMessages = {
        name: {
            valueMissing: 'Please fill out this field.'
        },
        brand: {
            valueMissing: 'Please fill out this field.'
        },
        category: {
            valueMissing: 'Please select a category.'
        },
        price: {
            valueMissing: 'Please fill out this field.',
            typeMismatch: 'Please enter a valid number.',
            rangeUnderflow: 'Please enter a value greater than or equal to 0.'
        }
    };

    function applyEnglishValidationMessage(field) {
        if (!field) {
            return;
        }

        if (field.validity.valid) {
            field.setCustomValidity('');
            return;
        }

        const messages = validationMessages[field.id] || {};
        let message = 'Please enter a valid value.';

        if (field.validity.valueMissing && messages.valueMissing) {
            message = messages.valueMissing;
        } else if (field.validity.typeMismatch && messages.typeMismatch) {
            message = messages.typeMismatch;
        } else if (field.validity.rangeUnderflow && messages.rangeUnderflow) {
            message = messages.rangeUnderflow;
        }

        field.setCustomValidity(message);
    }

    fields.forEach(function (field) {
        field.addEventListener('invalid', function () {
            applyEnglishValidationMessage(field);
        });

        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });

        field.addEventListener('change', function () {
            applyEnglishValidationMessage(field);
        });
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const dbSaveErrorDiv  = document.getElementById('db-save-error');
        const dbSaveErrorText = document.getElementById('db-save-error-text');
        dbSaveErrorDiv.classList.add('is-hidden');
        dbSaveErrorText.innerText    = '';

        const priceErrorSpan = document.getElementById('price-error');
        priceErrorSpan.classList.add('is-hidden');
        priceErrorSpan.innerText     = '';
        document.getElementById('price').classList.remove('is-invalid');

        try {
            const response = await fetch('index.php?action=create', {
                method: 'POST',
                body: new FormData(form)
            });
            const result = await response.json();

            if (result.success) {
                window.location.href = 'index.php?page=view_products';
            } else if (result.errors) {
                if (result.errors.db) {
                    dbSaveErrorText.innerText    = result.errors.db;
                    dbSaveErrorDiv.classList.remove('is-hidden');
                }
                if (result.errors.price) {
                    priceErrorSpan.innerText     = result.errors.price;
                    priceErrorSpan.classList.remove('is-hidden');
                    document.getElementById('price').classList.add('is-invalid');
                }
            } else if (result.error) {
                dbSaveErrorText.innerText    = result.error;
                dbSaveErrorDiv.classList.remove('is-hidden');
            }
        } catch (err) {
            dbSaveErrorText.innerText    = 'Network error saving product: ' + err.message;
            dbSaveErrorDiv.classList.remove('is-hidden');
        }
    });
}


document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById('products-tbody')) {
        loadProducts();
    }

    if (document.getElementById('product-form')) {
        initAddProductForm();
    }
});
