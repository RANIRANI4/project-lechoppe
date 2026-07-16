document.addEventListener('DOMContentLoaded', () => {
    const fields = document.querySelectorAll('[data-counter]');

    fields.forEach((field) => {
        const max = field.maxLength;

        if (max <= 0) {
            return;
        }

        const counter = document.createElement('small');
        counter.className = 'form-text d-block text-end';
        field.insertAdjacentElement('afterend', counter);

        const update = () => {
            const length = field.value.length;
            counter.textContent = `${length} / ${max} caractères`;

            counter.classList.toggle('text-danger', length >= max * 0.9);
        };

        field.addEventListener('input', update);
        update();
    });
});
