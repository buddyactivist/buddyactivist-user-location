(function(){
    function searchAddress() {
        const input = document.getElementById('baul-user-map-search-input');
        if (!input || !input.value) return;

        const q = encodeURIComponent(input.value);
        const url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + q;

        fetch(url, { headers: { 'User-Agent': 'BuddyActivist-User-Location/1.0' } })
            .then(res => res.json())
            .then(data => {
                if (!data[0]) return;
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);

                if (window.L && window.baulUserMapInstance) {
                    window.baulUserMapInstance.setView([lat, lon], 13);
                }
            });
    }

    document.addEventListener('DOMContentLoaded', function(){
        const btn = document.getElementById('baul-user-map-search-button');
        if (!btn) return;
        btn.addEventListener('click', function(e){
            e.preventDefault();
            searchAddress();
        });
    });
})();
