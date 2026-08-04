function openDialog(dialog) {
    if (dialog && typeof dialog.showModal === 'function') {
        dialog.showModal();
    }
}

function closeDialog(dialog) {
    if (dialog && typeof dialog.close === 'function') {
        dialog.close();
    }
}

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-dialog-target]');
    if (trigger) {
        openDialog(document.getElementById(trigger.dataset.dialogTarget));
        return;
    }

    const closeButton = event.target.closest('.sltk-dialog-close');
    if (closeButton) {
        closeDialog(closeButton.closest('dialog'));
        return;
    }

    if (event.target instanceof HTMLDialogElement) {
        closeDialog(event.target);
    }
});
