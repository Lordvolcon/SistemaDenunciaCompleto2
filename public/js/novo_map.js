const locationIQKey = 'pk.1294728d1a09262d5659d1c2475b2b2c';

var map = L.map('map').setView([-15.77972, -47.92972], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
var marker;

function setMarker(lat, lon) {
    if (marker) { 
        marker.setLatLng([lat, lon]); 
    } else { 
        marker = L.marker([lat, lon]).addTo(map); 
    }
    map.setView([lat, lon], 15);
    reverseGeocode(lat, lon);

    // ✅ Preenche os campos hidden
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lon;
}

map.on('click', function(e) {
    var latlng = e.latlng;
    setMarker(latlng.lat, latlng.lng);
});

function montarEndereco(address) {
    let endereco = `${address.road}, ${address.city}`;

    if (address.state) {
        endereco += `, ${address.state}`;
    }
    if (address.country) {
        endereco += `, ${address.country}`;
    }

    // Ajuste: substituir "Distrito Federal" por "Brasília, DF"
    endereco = endereco.replace(/Distrito Federal/gi, 'Brasília, DF');

    return endereco.trim();
}

function reverseGeocode(lat, lon) {
    const url = `https://us1.locationiq.com/v1/reverse.php?key=${locationIQKey}&lat=${lat}&lon=${lon}&format=json&addressdetails=1`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log("Reverso LocationIQ:", data);
            if (data && data.address) {
                let enderecoLimpo = montarEndereco(data.address);
                document.getElementById('localizacao').value = enderecoLimpo;
            } else {
                document.getElementById('localizacao').value = lat + ", " + lon;
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById('localizacao').value = lat + ", " + lon;
        });
}

function buscarEndereco() {
    var endereco = document.getElementById('busca').value.trim();
    console.log(endereco);
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
                    console.log("ViaCEP:", data);
                    let buscaCompleta = "";
                    if (data.logradouro) {
                        buscaCompleta = `${data.logradouro}, ${data.bairro}, ${data.localidade}, ${data.uf}, Brasil`;
                    } else {
                        buscaCompleta = `${data.bairro}, ${data.localidade}, ${data.uf}, Brasil`;
                    }
                    console.log("Busca final no LocationIQ:", buscaCompleta);
                    buscaCompleta = buscaCompleta.replace(/\bQuadra\b/gi, '').trim();
                    buscarNoLocationIQ(buscaCompleta);
                } else {
                    alert("CEP não encontrado.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erro ao consultar o CEP.");
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
            console.log("Resposta LocationIQ:", data);
            if (data && data.length > 0) {
                const lat = data[0].lat;
                const lon = data[0].lon;
                setMarker(lat, lon);
                let endereco = data[0].display_name.replace(/\bQuadra\b\s?/gi, '');
                endereco = endereco.replace(/Distrito Federal/gi, 'Brasília, DF');
                document.getElementById('localizacao').value = endereco.trim();
            } else {
                alert("Endereço não encontrado.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro ao buscar endereço.");
        });
}