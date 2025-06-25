<div class="flex items-center justify-between mt-2" x-data>
    <button
        type="button"
        @click="
            let textarea = $el.closest('.fi-fo-repeater-item')?.querySelector('textarea');
            if (textarea?.value) {
                navigator.clipboard.writeText(textarea.value)
            }
        "
        class="px-4 py-2 bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
    >
        Copiar texto
    </button>
</div>
