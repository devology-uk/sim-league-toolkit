function initTabs(tabsEl) {
    const buttons = Array.from(tabsEl.querySelectorAll(':scope > .sltk-tab > .sltk-tab-button'));
    const panels = Array.from(tabsEl.querySelectorAll(':scope > .sltk-tab > .sltk-tab-panel'));

    function activate(index) {
        buttons.forEach((button, i) => {
            button.setAttribute('aria-selected', String(i === index));
        });
        panels.forEach((panel, i) => {
            panel.hidden = i !== index;
        });
    }

    buttons.forEach((button, index) => {
        button.addEventListener('click', () => activate(index));
    });

    if (buttons.length > 0) {
        activate(0);
    }
}

document.querySelectorAll('.sltk-tabs').forEach(initTabs);
