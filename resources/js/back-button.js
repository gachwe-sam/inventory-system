document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-back=button]');
    if (!button) return;

    const cameFromThisSite = document.referrer.startsWith(window.location.origin);

    if(cameFromTHisSite && window.history.length > 1){
        event.preventDefault();
        window.history.back();        
    }
});