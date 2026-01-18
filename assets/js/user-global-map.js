(function(){
    let map;
    let markers = [];

    function initMap() {
        const el = document.getElementById('baul-user-map');
        if (!el) return;

        map = L.map(el).setView([41.9, 12.5], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        fetch(BAUL_User_Map.rest_url)
            .then(res => res.json())
            .then(users => {
                users.forEach(user => {
                    const icon = L.icon({
                        iconUrl: BAUL_User_Map.marker_icon,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    });

                    const marker = L.marker([user.lat, user.lng], { icon }).addTo(map);
                    marker.bindPopup(
                        '<strong>' + user.name + '</strong><br>' +
                        '<img src="' + user.avatar + '" style="width:40px;height:40px;border-radius:50%;margin:5px 0;"><br>' +
                        '<a href="' + user.profile_url + '">' + 'Profile' + '</a>'
                    );
                    markers.push(marker);
                });
            });
    }

    document.addEventListener('DOMContentLoaded', initMap);
})();
