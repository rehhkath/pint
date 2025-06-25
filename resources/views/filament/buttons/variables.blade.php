<div class="button-container">
    <button class="button_variable" type="button" onclick="insertVariable('|*Nome do Vendedor*|')">
        Nome do vendedor
    </button>
    <button class="button_variable" type="button" onclick="insertVariable('|*Codigo do Vendedor*|')">
        Código do vendedor
    </button>
    <button class="button_variable" type="button" onclick="insertVariable('|*Loja do Vendedor*|')">
        Loja do vendedor
    </button>
    <button class="button_variable" type="button" onclick="insertVariable('|*Nome do cliente*|')">
        Nome do cliente
    </button>
    <button class="button_variable" type="button" onclick="insertVariable('|*Data expiração cashback*|')">
        Expiração do cashback
    </button>
    <button class="button_variable" type="button" onclick="insertVariable('|*Valor do chashback*|')">
        Cashback disponível
    </button>
</div>

<style>
.button-container {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
    justify-content: start; 
}

.button_variable {
    width: 100%;
    padding: 10px 15px; 
    font-size: 14px; 
    border: 1px solid #ddd; 
    border-radius: 5px; 
    background-color: #f9f9f9; 
    cursor: pointer; 
}

.button_variable:hover {
    background-color: #e0e0e0; 
}

</style>

<script>
    let lastFocusedTextarea = null;

    document.addEventListener('focusin', (event) => {
        if (event.target.tagName === 'TEXTAREA') {
            lastFocusedTextarea = event.target;
        }
    });

    function insertVariable(variable) {
        const textarea = lastFocusedTextarea;

        if (!textarea) {
            console.warn('Nenhum campo de texto está selecionado.');
            return;
        }

        const { selectionStart = 0, selectionEnd = 0, value = '' } = textarea;

        textarea.value =
            value.substring(0, selectionStart) +
            variable +
            value.substring(selectionEnd);

        textarea.dispatchEvent(new Event('input', { bubbles: true }));
    }
</script>
