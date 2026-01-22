document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('typeSelect');
    const dynamic = document.getElementById('dynamicFields');
    const preview = document.getElementById('studentPreview');

    if (!typeSelect) return;

    typeSelect.addEventListener('change', renderFields);

    function renderFields() {
        dynamic.innerHTML = '';
        preview.innerHTML = '';

        switch (typeSelect.value) {

            case 'short_answer':
                preview.innerHTML = `
                    <input disabled placeholder="Student types a short answer">
                `;
                break;

            case 'yes_no':
                preview.innerHTML = `
                    <label><input type="radio" disabled> Yes</label><br>
                    <label><input type="radio" disabled> No</label>
                `;
                dynamic.innerHTML = `
                    <p class="hint">Correct answer</p>
                    <select name="correct_answer" required>
                        <option value="">Select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                `;
                break;

            case 'single_choice':
            case 'multiple_choice':
            case 'multiple_response':
                dynamic.innerHTML = `
                    <h4>Choices</h4>
                    <div id="choices"></div>
                    <button type="button" id="addChoice" class="btn-secondary small">
                        + Add Choice
                    </button>
                    <p class="hint">Mark correct answers below</p>
                `;
                preview.innerHTML = `<p class="muted">Choices will appear here</p>`;
                document.getElementById('addChoice').addEventListener('click', addChoice);
                break;
        }
    }

    function addChoice() {
        const choices = document.getElementById('choices');
        const index = choices.children.length;

        const row = document.createElement('div');
        row.className = 'choice-row';

        row.innerHTML = `
            <input type="text" name="choices[]" placeholder="Choice text" required>
            <input type="checkbox" name="correct_choices[]" value="${index}">
            <button type="button" class="btn-danger mini">✕</button>
        `;

        row.querySelector('button').onclick = () => row.remove();
        choices.appendChild(row);
    }
});
