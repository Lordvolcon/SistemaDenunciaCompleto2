    const locationIQKey = 'pk.1294728d1a09262d5659d1c2475b2b2c';

    var map = L.map('map').setView([-15.77972, -47.92972], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
    var marker;

    function setMarker(lat, lon) {
        if (marker) { marker.setLatLng([lat, lon]); }
        else { marker = L.marker([lat, lon]).addTo(map); }
        map.setView([lat, lon], 15);
        reverseGeocode(lat, lon);
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lon;
    }

    map.on('click', function(e) {
        var latlng = e.latlng;
        setMarker(latlng.lat, latlng.lng);
    });

    function reverseGeocode(lat, lon) {
        const url = `https://us1.locationiq.com/v1/reverse.php?key=${locationIQKey}&lat=${lat}&lon=${lon}&format=json`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    document.getElementById('localizacao').value = data.display_name;
                } else {
                    document.getElementById('localizacao').value = lat + ", " + lon;
                }
            });
    }

    function buscarEndereco() {
        var endereco = document.getElementById('busca').value.trim();
        if (!endereco) {
            alert("Digite um endereço ou CEP.");
            return;
        }

        if (/^\d{8}$/.test(endereco) || /^\d{5}-?\d{3}$/.test(endereco)) {
            endereco = endereco.replace("-", "");
            if (endereco.length !== 8) {
                alert("CEP inválido. Digite no formato 00000-000 ou 00000000.");
                return;
            }
            fetch(`https://viacep.com.br/ws/${endereco}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        let buscaCompleta = data.logradouro 
                            ? `${data.logradouro}, ${data.bairro}, ${data.localidade}, ${data.uf}, Brasil` 
                            : `${data.bairro}, ${data.localidade}, ${data.uf}, Brasil`;
                        buscaCompleta = buscaCompleta.replace(/\bQuadra\b/gi, '').trim();
                        buscarNoLocationIQ(buscaCompleta);
                    } else {
                        alert("CEP não encontrado.");
                    }
                });
        } else {
            buscarNoLocationIQ(endereco);
        }
    }

    function buscarNoLocationIQ(endereco) {
        const url = `https://us1.locationiq.com/v1/search.php?key=${locationIQKey}&q=${encodeURIComponent(endereco)}&format=json`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = data[0].lat;
                    const lon = data[0].lon;
                    setMarker(lat, lon);
                } else {
                    alert("Endereço não encontrado.");
                }
            });
    }