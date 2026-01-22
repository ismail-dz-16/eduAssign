const typeSelect = document.getElementById('typeSelect');
const builder = document.getElementById('answerBuilder');
const preview = document.getElementById('studentPreview');

typeSelect?.addEventListener('change', () => {
    builder.innerHTML = '';
    preview.innerHTML = '';

    switch (typeSelect.value) {

        case 'short_answer':
            preview.innerHTML = `<input type="text" disabled placeholder="Student short answer">`;
            break;

        case 'yes_no':
            preview.innerHTML = `
                <label><input type="radio" disabled> Yes</label>
                <label><input type="radio" disabled> No</label>
            `;
            break;

        case 'single_choice':
        case 'multiple_choice':
            builder.innerHTML = `
                <h4>Choices</h4>
                <div id="choices"></div>
                <button type="button" id="addChoice" class="btn-secondary">
                    + Add Choice
                </button>
            `;
            preview.innerHTML = `<p class="muted">Choices will appear here</p>`;

            document.getElementById('addChoice').onclick = addChoice;
            break;
    }
});

function addChoice() {
    const choices = document.getElementById('choices');

    const index = choices.children.length;

    const row = document.createElement('div');
    row.className = 'choice-row';
    row.innerHTML = `
        <input type="text" name="choices[]" placeholder="Choice text" required>
        <button type="button" class="btn-danger small">✕</button>
    `;

    row.querySelector('button').onclick = () => row.remove();
    choices.appendChild(row);
}
