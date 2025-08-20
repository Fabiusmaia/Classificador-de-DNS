# Desafio Técnico: Classificação de Logs DNS usando IA

## Objetivo

Aplicação em **Laravel** que processa **logs de consultas DNS (simulados)** e utiliza uma **API de Inteligência Artificial** para classificar o risco de cada consulta em três níveis:

- ✅ **Seguro**
- ⚠️ **Suspeito**
- ❌ **Malicioso**

Os resultados são armazenados no banco e podem ser consultados com filtros.

---

## Tecnologias Utilizadas

- PHP 8.2
- Laravel 12
- Postgres
- Laravel Breeze ou Jetstream para autenticação

---


## Como Rodar na Máquina

1. Clone o repositório:

```bash
git clone https://github.com/Fabiusmaia/Teste-lumiun.git
cd Teste-lumiun
```

2. Instale as dependências:
```
composer install
npm install
npm run dev
```

3. Configure o arquivo .env:
```
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados no .env:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=teste_dns
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

5. Rode as migrations:
```
php artisan migrate
```

6. Execute o servidor local:
```
php artisan serve
```

O projeto ficará disponível em http://127.0.0.1:8000


# Obtendo a API Key

1. Crie uma conta ou registre-se na plataforma **Gemini**.  
2. Gere sua **API Key** na área de desenvolvedor.  
3. Adicione a chave no arquivo `.env` do projeto:

```env
GEMINI_API_KEY=SuaChaveAqui
```


## Usando o CSV de Mock

1. No repositório já existe um arquivo de exemplo: `dns-mock.csv`.

2. No painel da aplicação, vá até **Upload de Logs DNS** e selecione o arquivo `dns-mock.csv`.

3. O sistema processará cada linha, chamando a API de IA para classificar os domínios e armazenar os resultados no banco de dados.

