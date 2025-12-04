# Conect24

Conect24 é um protótipo simples de rede social em PHP para estudo. Inclui cadastro/login, feed com postagens de texto e imagem, página de perfil com edição de dados e upload de foto.

## Tecnologias
- PHP 8+ com sessões nativas
- MySQL/MariaDB (script em `CONECT24.sql`)
- HTML/CSS estático (`estilo.css`)

## Estrutura rápida
- `index.php` – tela de login
- `cadastro.php` – formulário de criação de conta
- `home.php` – feed com postagens e upload de imagem
- `perfil.php` – visão do perfil e listagem das suas postagens
- `editar_perfil.php` – edição de nome, e-mail, data de nascimento, bio e foto
- `postar.php` / `logout.php` – criação de post e saída da sessão
- `uploads/` – onde as imagens são gravadas (criado automaticamente quando faltante)
- `CONECT24.sql` – script para criar o schema e tabelas

## Como rodar localmente (XAMPP/WAMP/LAMP)
1. Crie um banco no MySQL executando `CONECT24.sql`.
2. Ajuste as credenciais em `config.php` (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).
3. Coloque o projeto dentro do diretório servido pelo Apache (ex.: `htdocs/conect24`) e habilite o módulo PHP.
4. Garanta que a pasta `uploads/` seja gravável pelo servidor web (criada automaticamente em alguns fluxos).
5. Acesse `http://localhost/conect24` para ver a tela de login. Use “Criar nova conta” para registrar um usuário e, após logar, poste no feed ou edite o perfil.

## Fluxos principais
- Cadastro → `cadastro.php` grava na tabela `PERFIL` (hash SHA-256 simples).
- Login → `login.php` autentica e redireciona para `perfil.php`.
- Postagem → `postar.php` salva texto e imagem opcional na tabela `POSTAGEM`; imagens ficam em `uploads/`.
- Perfil → `perfil.php` mostra idade calculada por `DATA_NASC`, bio e postagens do usuário.
- Edição → `editar_perfil.php` permite alterar dados e subir nova foto de perfil.

## Observações e pontos de atenção
- Não há middleware de autenticação robusto nem filtragem de entrada além do escape básico; use apenas em ambiente de estudo.
- As senhas são armazenadas com `hash('sha256')` sem salt; para produção troque para `password_hash/password_verify`.
- Falta controle de tamanho/validação de uploads e de tipos de arquivo.
- O script de comunidades (`cadastrar-comunidades.html`) é apenas estático e não persiste dados.
