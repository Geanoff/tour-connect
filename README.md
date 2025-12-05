# Tour Connect

Aplicação web simples para agendamento de passeios turísticos, com gerenciamento de destinos, guias e agendamentos dos locais.

Projeto em PHP e Banco de Dados MySQL.

## Estrutura do projeto

- `config/database.php` - configuração da conexão PDO com o banco de dados (host, porta, dbname, usuário e senha).
- `config/estrutura/` - script SQL para criar o schema do banco de dados.
- `config/cargas/` - script SQL com dados de exemplo (destinos, guias, imagens e vínculos).
- `controller/` - controladores (lógica de fluxo entre view e model).
- `model/` - modelos responsáveis por operações com o banco (PDO).
- `view/` - arquivos públicos (HTML/PHP), CSS e JS.
- `uploads/` - imagens enviadas (destinos e guias).

## Pré-requisitos

- PHP 7.4+ (compatível com versões mais novas do PHP 8.x)
- MySQL / MariaDB
- XAMPP (ou outro ambiente Apache + PHP + MySQL)

## Configuração local (Windows + XAMPP)

1. Coloque a pasta do projeto dentro da pasta pública do XAMPP. Exemplo padrão:

   C:\xampp\htdocs\tour-connect

2. Inicie no painel do XAMPP o Apache e o MySQL.

3. Crie o banco de dados e importe os scripts SQL:

   - Abra o phpMyAdmin (`http://localhost/phpmyadmin`) ou use a linha de comando MySQL.
   - Crie o banco `tourconnect` (o script `estrutura.sql` já contém `create database if not exists tourconnect;`).
   - Importe `config/estrutura/estrutura.sql` para criar as tabelas.
   - Importe `config/cargas/carga.sql` para popular com dados de exemplo.

   Observação: por padrão o `config/database.php` do projeto já usa os dados:

   - host: `localhost`
   - port: `3306`
   - dbname: `tourconnect`
   - user: `root`
   - password: `` 

   Caso seu ambiente use credenciais diferentes, edite o arquivo `config/database.php`.

## Como rodar

1. Abra o navegador e acesse a URL do projeto. Se colocou dentro de `htdocs`:

   http://localhost/tour-connect/

2. A rota inicial (`index.php`) redireciona para `view/pages/home.php`.

## Notas de desenvolvimento

- As conexões com o banco usam PDO em `config/database.php`.
- Scripts de model estão em `model/` (ex.: `DestinoModel.php`, `GuiaModel.php`).
- As views usam componentes em `view/components/` e assets em `view/assets/`.

## Checklists rápidas

- [ ] Importar `estrutura.sql`
- [ ] Importar `carga.sql` (dados de exemplo)
- [ ] Ajustar `config/database.php` se usar outro usuário/senha

# Senac - Hackathon Voucher Desenvolvedor