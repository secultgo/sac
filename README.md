# SAC - Sistema de Atendimento de Chamados

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
</p>

## 📋 Sobre o Projeto

O **SAC (Sistema de Atendimento de Chamados)** é uma aplicação web desenvolvida para gerenciar chamados de suporte técnico e atendimento ao cliente. O sistema oferece um fluxo completo de abertura, acompanhamento, atendimento e resolução de chamados, com diferentes níveis de acesso e permissões.

### 🎯 Principais Funcionalidades

- **Abertura de Chamados** - Interface intuitiva para abertura de solicitações
- **Dashboard Interativo** - Painéis com estatísticas e gráficos em tempo real
- **Sistema de Permissões** - 4 níveis de acesso (Super Usuário, Gestor, Atendente, Usuário)
- **Gestão de Equipes** - Controle de usuários por departamento
- **Avaliação de Atendimento** - Sistema de feedback com ícones interativos
- **Relatórios Detalhados** - Exportação em PDF e Excel com DataTables
- **Atribuição de Responsáveis** - Distribuição automática e manual de chamados
- **Timeline de Atividades** - Histórico completo de todas as interações
- **Sistema de Status** - Controle de fluxo (Aberto → Atendimento → Pendente → Resolvido → Fechado)

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 11** - Framework PHP
- **PHP 8.2** - Linguagem de programação
- **MySQL 8.0** - Banco de dados
- **Eloquent ORM** - Mapeamento objeto-relacional

### Frontend
- **AdminLTE 3** - Template administrativo
- **Bootstrap 4** - Framework CSS
- **jQuery** - Biblioteca JavaScript
- **Chart.js** - Gráficos interativos
- **DataTables** - Tabelas avançadas com filtros e exportação
- **SweetAlert2** - Alertas e modais elegantes
- **Font Awesome** - Ícones

### Ferramentas e Infraestrutura
- **Docker & Docker Compose** - Containerização
- **Nginx** - Servidor web
- **Redis** - Cache e sessões
- **Composer** - Gerenciador de dependências PHP
- **NPM/Vite** - Build de assets frontend

## 📦 Instalação e Configuração

### Pré-requisitos
- Docker e Docker Compose
- Git

### 1. Clone do Repositório
```bash
git clone https://github.com/secultgo/sac.git
cd sac
```

### 2. Configuração do Ambiente
```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Configurar variáveis no .env (banco de dados, etc.)
```

### 3. Inicialização com Docker
```bash
# Subir os containers
docker-compose up -d

# Instalar dependências PHP
docker exec -it sac-app composer install

# Gerar chave da aplicação
docker exec -it sac-app php artisan key:generate

# Executar migrations
docker exec -it sac-app php artisan migrate

# Executar seeders (dados iniciais)
docker exec -it sac-app php artisan db:seed
```

### 4. Instalação dos Assets
```bash
# Instalar dependências Node
docker exec -it sac-app npm install

# Build dos assets
docker exec -it sac-app npm run build
```

## 🚀 Uso do Sistema

### Acessos Padrão (após seeders)
- **Super Usuário**: admin@sistema.com / senha123
- **Gestor**: gestor@sistema.com / senha123
- **Atendente**: atendente@sistema.com / senha123
- **Usuário**: usuario@sistema.com / senha123

### URLs Principais
- **Aplicação**: http://localhost:8000
- **Dashboard**: http://localhost:8000/painel/dashboard
- **Relatórios**: http://localhost:8000/painel/relatorios
- **Gráficos**: http://localhost:8000/painel/graficos

## 📊 Estrutura do Sistema

### Níveis de Acesso
1. **Super Usuário** - Acesso total ao sistema
2. **Gestor** - Gerência de departamento e equipe
3. **Atendente** - Atendimento e resolução de chamados
4. **Usuário** - Abertura e acompanhamento de chamados

### Fluxo de Chamados
```
Aberto → Atendimento → Pendente → Resolvido → Avaliado → Fechado
                    ↘ Aguardando Usuário ↗
                    ↘ Transferido ↗
```

### Status Disponíveis
- 🔴 **Aberto** - Aguardando início do atendimento
- 🟡 **Atendimento** - Em andamento
- 🟠 **Pendente** - Aguardando informações/recursos
- 🔵 **Aguardando Usuário** - Solicitação de informações ao solicitante
- 🟢 **Resolvido** - Aguardando avaliação
- ✅ **Fechado** - Concluído e avaliado
- 🟣 **Reaberto** - Reaberto após fechamento

## 🔧 Comandos Úteis

### Docker
```bash
# Visualizar logs
docker-compose logs -f

# Parar containers
docker-compose down

# Rebuild dos containers
docker-compose up -d --build
```

### Laravel (dentro do container)
```bash
# Acessar container
docker exec -it sac-app bash

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rodar migrations específicas
php artisan migrate --path=/database/migrations/2025_06_16_120954_create_status_table.php

# Executar seeders específicos
php artisan db:seed --class=StatusSeeder
php artisan db:seed --class=NivelSeeder
```

## 📈 Recursos Avançados

### Gráficos e Relatórios
- Dashboard com 6 tipos de gráficos interativos
- Exportação PDF em paisagem com colunas otimizadas
- Filtros por departamento, período e status
- Tabelas responsivas com busca avançada

### Sistema de Avaliação
- 4 níveis de avaliação com ícones Font Awesome
- Notificações automáticas para avaliações pendentes
- Relatórios de satisfação por departamento

### Gestão de Equipes
- Controle de cores por usuário (20 cores disponíveis)
- Atribuição automática e manual de responsáveis
- Filtros por departamento e nível de acesso

## 🔐 Segurança

- Autenticação Laravel nativa
- Sistema de permissões por Gates
- Proteção CSRF em todos os formulários
- Validação de dados server-side
- Sanitização de inputs

## 📝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas alterações (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença [MIT](https://opensource.org/licenses/MIT).

## 🤝 Suporte

Para suporte e dúvidas:
- **Email**: suporte@sistema.com
- **Issues**: [GitHub Issues](https://github.com/secultgo/sac/issues)

---

<p align="center">
  Desenvolvido pela Gerência de Tecnologia da Secretaria de Cultura do Estado de Goiás
</p>
