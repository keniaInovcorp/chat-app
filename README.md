# Sistema de Chat

Sistema de chat em tempo real desenvolvido com Laravel, permitindo mensagens diretas entre membros da equipa e comunicação em salas de chat.

## Descrição

Esta aplicação oferece um sistema completo de mensagens instantâneas, possibilitando:

- Mensagens diretas entre utilizadores
- Criação e gestão de salas de chat
- Convite de utilizadores para salas específicas
- Sistema de permissões com diferentes níveis de acesso

## Estrutura de Dados

### Utilizadores

A tabela de utilizadores contém os seguintes campos:

- **Avatar**: Imagem de perfil do utilizador
- **Nome**: Nome completo do utilizador
- **Email**: Endereço de email único
- **Permissão**: Nível de acesso do utilizador
  - Admin
  - User
- **Estado**: Status atual do utilizador (online/offline)

### Salas

A estrutura das salas de chat inclui:

- **Avatar**: Imagem identificadora da sala
- **Nome**: Designação da sala
- **Utilizadores**: Lista de membros com acesso à sala

## Permissões

Apenas utilizadores com permissão de **Admin** têm capacidade de:

- Criar e gerir salas de chat
- Convidar utilizadores para salas
- Administrar membros e permissões

## Referência de Interface

A interface e experiência do utilizador seguem o modelo da aplicação Campfire:

- Referência: https://once.com/campfire
- Demo visual: https://x.com/jasonfried/status/1748097864625205586

## Requisitos Técnicos

- PHP 8.1 o
- Laravel 10.x
- MySQL 8.0
- Composer
- Node.js e NPM

## Configuração

### Utilizador Admin Padrão

Após executar os seeders, será criado um utilizador administrador. As credenciais de acesso encontram-se no ficheiro `database/seeders/DatabaseSeeder.php`.

## Tecnologias Utilizadas

- **Backend**: Laravel Framework
- **Frontend**: Blade Templates, JavaScript
- **Base de Dados**: MySQL
- **Autenticação**: Laravel Authentication
- **Real-time**: WebSockets/Broadcasting

## Estrutura do Projeto

```
chat-app/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   └── js/
└── routes/
    └── web.php
```

## Licença

Este projeto foi desenvolvido em contexto académico.
