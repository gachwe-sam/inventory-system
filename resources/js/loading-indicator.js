document.addEventListener('submit',(event) =>{
    const button =  event.submitter;
    if (!button) return;

    const form = button.closest('form');
    const indicator = button.querySelector('.spinner-border, .loading-dots') ?? form?.querySelector('progress');

    if (!indicator) return;
    indicator.classList.remove('d-none');
    button.disabled = true;
});