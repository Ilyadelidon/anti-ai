window.addEventListener('load', function(){
    if (window.history.replaceState) {
        window.history.replaceState(null, '', window.location.pathname);
    }
});