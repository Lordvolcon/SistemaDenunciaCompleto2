# SistemaDenunciaCompleto2

Este projeto utiliza variáveis de ambiente para a configuração do banco de dados e para a chave da API do LocationIQ.

## Variáveis de ambiente

Defina as seguintes variáveis antes de executar a aplicação:

- `DB_HOST`  – endereço do servidor MySQL
- `DB_USER`  – usuário do banco
- `DB_PASSWORD` – senha do banco

Exemplo de definição em um shell:

```bash
export DB_HOST=localhost
export DB_USER=meu_usuario
export DB_PASSWORD=minha_senha
```

## Chave do LocationIQ

Os arquivos `public/js/map.js` e `public/js/novo_map.js` esperam que a chave da API seja atribuída à variável global `LOCATIONIQ_KEY`. Inclua um bloco de script antes de carregar esses arquivos:

```html
<script>
  window.LOCATIONIQ_KEY = 'sua-chave-locationiq';
</script>
<script src="js/novo_map.js"></script>
```

Os uploads de imagens são gravados no diretório `uploads/`, que está listado no `.gitignore` para evitar o versionamento desses arquivos.

