# SalesTrack

Sistema de cadastro de vendedores e vendas, com cálculo de comissões e envio de e-mails diários.

## Requisitos
- Docker
- Docker Compose
- Node.js (para build frontend, se necessário)

## Como Rodar o Projeto

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

2. Suba os containers:

```bash
docker-compose up -d --build
```
3. Acesse o container da aplicação (backend):

```bash
docker exec -it salestrack-app bash
```
4. Rode as migrations e seeders dentro do container:

```bash
php artisan migrate --seed
```
## Acesso aos Serviços
- Frontend (Vue.js): http://localhost:8080


- Backend API (Laravel): http://localhost:8000

- Mailhog (Captura de e-mails): http://localhost:8025

## Observações
- O frontend foi compilado para produção e servido pelo http-server na porta 8080.

- A API Laravel está rodando na porta 8000.

- O Mailhog é usado para visualizar os e-mails enviados durante os testes.

- Existe um container cron responsável pelo agendamento de tarefas diárias (envio de e-mails automáticos).

- Tecnologias Utilizadas
    - Laravel
    - Vue.js (com TypeScript)
    - Pinia (gerenciamento de estado)
    - MySQL
    - TailwindCSS
    - Docker
    - Axios