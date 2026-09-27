# 🚀 Deep Learning Vigilância - Backend (API)

Este é o repositório do backend da aplicação. O projeto utiliza
**Laravel Sail** (Docker) para o ambiente de desenvolvimento, provendo uma
infraestrutura completa com banco de dados (PostgreSQL), simulação de filas
locais (Redis) e armazenamento de arquivos compatível com S3 (Silo/MinIO).

## 📋 Pré-requisitos

- [Docker](https://www.docker.com) e Docker Compose
  instalados e rodando.
- [Composer](https://getcomposer.org) e PHP instalados localmente na
  sua máquina (opcional, mas recomendado para a primeira instalação).

---

## 🛠️ Passo a Passo para Instalação

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd deep-learning-vigilancia-backend

```

### 2. Configure as variáveis de ambiente

Crie o seu arquivo de configuração local copiando o arquivo de exemplo:

```bash
cp .env.example .env

```

_(Nota: O `.env.example` já vem pré-configurado com as credenciais padrão do
Docker. Não é necessário alterar senhas para o ambiente de desenvolvimento)._

### 3. Instale as dependências

Se você possui o PHP/Composer instalados no seu sistema operacional
(ex: Arch Linux, Ubuntu, MacOS), rode:

```bash
composer install

```

> 💡 **Não tem o PHP localmente?** Sem problemas. Você pode usar um container
> descartável do Docker para instalar as dependências rodando:
>
> ```bash
> docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" \
>   -w /var/www/html laravelsail/php83-composer:latest \
>   composer install --ignore-platform-reqs
>
> ```

### 4. Suba a infraestrutura do projeto

Inicie os containers do Laravel Sail em segundo plano:

```bash
./vendor/bin/sail up -d

```

### 5. Gere a chave da aplicação e configure o Banco de Dados

Com os containers rodando, gere a chave de criptografia do Laravel:

```bash
./vendor/bin/sail artisan key:generate

```

Em seguida, rode as migrations para criar as tabelas no PostgreSQL:

```bash
./vendor/bin/sail artisan migrate

```

### 6. Valide a instalação (Testes)

Para garantir que a comunicação com o banco de dados e o ambiente de testes subiram corretamente, rode a suíte de testes:

```bash
./vendor/bin/sail test
```

---

## 📦 Serviços Locais (Simulando AWS)

Para facilitar o desenvolvimento, este projeto não bate na infraestrutura
real da AWS no ambiente local.

### 🪣 Armazenamento (S3 Local)

Utilizamos o **Silo** (fork do MinIO) para receber os uploads de arquivos
e testes de _Pre-signed URLs_.

- **Painel de Controle:** <http://localhost:8900>
- **Usuário:** `sail`
- **Senha:** `password`

### 📨 Filas de Processamento (SQS Local)

Localmente, usamos o **Redis** (`QUEUE_CONNECTION=redis` no `.env`) para
gerenciar as filas. A aplicação despacha os Jobs normalmente, e eles
ficam armazenados no Redis.

_(Lembrete: Em produção, o `.env` utilizará o driver `sqs` para enviar as
mensagens para a AWS, que serão consumidas pelo Modal.com)._

---

## 💻 Comandos Úteis (Atalho do Sail)

Para não precisar digitar `./vendor/bin/sail` o tempo todo,
recomendamos criar um alias no seu sistema.

Adicione esta linha no seu `~/.bashrc` ou `~/.zshrc`:

```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'

```

Depois de reiniciar o terminal (ou rodar `source ~/.zshrc`), você
poderá usar comandos curtos e rápidos:

- `sail up -d` (Sobe o ambiente)
- `sail down` (Derruba o ambiente)
- `sail test` (Roda os testes automatizados)
- `sail artisan make:controller NomeController`
