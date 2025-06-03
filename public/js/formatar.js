        const loginInput = document.getElementById('login');

        loginInput.addEventListener('input', function () {
            const valor = loginInput.value;
            const soNumeros = valor.replace(/\D/g, '');
        
            // Detecta se não contém @ e não contém letras
            const pareceCpf = !/[a-zA-Z@]/.test(valor);
        
            if (pareceCpf && soNumeros.length <= 11) {
                let formatado = soNumeros;
                formatado = formatado.replace(/^(\d{3})(\d)/, '$1.$2');
                formatado = formatado.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                formatado = formatado.replace(/\.(\d{3})(\d)/, '.$1-$2');
                loginInput.value = formatado;
            }
        });