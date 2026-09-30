# Sistema de Ordens de Serviço para Marcenaria

Sistema web que digitaliza o envio de pedidos entre o **atendimento** e a **marcenaria**, que trabalham em locais diferentes. Antes, os pedidos eram passados por rádio, o que gerava ruído na comunicação e erros no corte das peças.

> **Status:** protótipo funcional, desenvolvido para uma madeireira real. A empresa optou por não adotá-lo, então nunca rodou em produção. Por isso este repositório não traz métricas de uso.

## Como funciona

O atendente cadastra o pedido do cliente (material, espessura, descrição e plano de corte em PDF/DXF). A marcenaria acompanha as ordens em um painel e vai atualizando o status:

`Recebido → Aguardando Produção → Produzindo → Pronto → Retirado/Encaminhado`

A listagem funciona como uma fila: o que está em aberto aparece primeiro e, dentro de cada etapa, o pedido mais antigo vem antes.

## Funcionalidades

- Login com sessão e logout
- Cadastro de ordens com anexo do plano de corte (PDF ou DXF, até 5 MB)
- Painel com busca (nº, cliente, descrição) e filtro por status
- Detalhes da ordem com material, espessura e download do anexo
- Atualização de status e exclusão de ordens
- Relatório com a contagem de pedidos por etapa (cartões clicáveis)

## Tecnologias

PHP (mysqli) · MySQL · JavaScript (puro, sem framework) · HTML5 · CSS3

## Como rodar localmente

Precisa de um servidor com PHP 8+ (Apache do XAMPP, por exemplo) e de um MySQL/MariaDB.

1. Copie a pasta do projeto para a raiz do servidor (no XAMPP: `C:\xampp\htdocs\sistema-ordens-servico`).
2. Crie o banco executando o `schema.sql` no seu MySQL (phpMyAdmin, MySQL Workbench ou `mysql -u root -p < schema.sql`).
3. Copie `config.example.php` para `config.local.php` e preencha com o host, a porta, o usuário, a senha e o nome do banco.
4. Crie um usuário de login pelo terminal, dentro da pasta do projeto (senha com 8+ caracteres):
   ```
   php criar_usuario.php admin SuaSenhaAqui123
   ```
5. Acesse `http://localhost/sistema-ordens-servico/login.php`.
6. (Opcional) Coloque sua logo em `assets/img/logo.png`.

O `config.local.php` não é versionado, então a senha do banco não vai para o repositório.

## Estrutura

```
├── index.php              Painel de ordens
├── nova-ordem.php         Formulário de cadastro
├── relatorios.php         Contagem por status
├── login.php / logout.php Autenticação
├── listar_ordens.php      ┐
├── salvar_ordem.php       │ Endpoints JSON (todos exigem login)
├── atualizar_status.php   │
├── excluir_ordem.php      ┘
├── baixar_anexo.php       Entrega do plano de corte (exige login)
├── funcoes.php            Sessão, proteção de rotas, respostas JSON
├── conexao.php            Conexão com o banco
├── config.example.php     Modelo de configuração (copie para config.local.php)
├── criar_usuario.php      Cria usuários (só roda pelo terminal)
├── schema.sql             Estrutura do banco
├── assets/                CSS, JavaScript e cabeçalho
└── storage/anexos/        Planos de corte enviados (fora do acesso público)
```

## Decisões técnicas

- **Consultas preparadas** em todas as queries (proteção contra SQL Injection).
- **Senhas com `password_hash`/`password_verify`**; a mensagem de erro do login não revela se o usuário existe.
- **Sessão protegida:** cookie `HttpOnly` e `SameSite=Strict`, e `session_regenerate_id` após o login.
- **Todos os endpoints exigem login.** Sessão expirada devolve 401 e o front leva para a tela de login.
- **Validação no servidor** (campos, listas de materiais/espessuras/status, extensão, tamanho e assinatura do PDF), não só no navegador.
- **Sem `innerHTML` com dados do usuário:** a tabela é montada com `textContent`, o que evita XSS armazenado.
- **Anexos em disco**, com nome aleatório, em pasta bloqueada para acesso direto (`storage/.htaccess`). Eles só saem pelo `baixar_anexo.php`, depois de checar o login. O banco guarda só o nome original e o nome no disco.

## Limitações conhecidas e próximos passos

- Só existe um tipo de usuário (sem perfis, por exemplo atendente × marcenaria).
- Sem token CSRF. A proteção atual é o `SameSite=Strict`.
- Sem paginação: a listagem carrega todas as ordens de uma vez.
- Sem histórico de mudanças de status (quem mudou e quando).
- Sem testes automatizados.
- O bloqueio de `storage/` depende do Apache; em Nginx é preciso configurar à parte (ou mover a pasta para fora da raiz pública).
