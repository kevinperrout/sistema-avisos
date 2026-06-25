# Acme Avisoos (2025-2)

Aplicação desenvolvida para a disciplina de Projeto Integrador de Sistemas do CEFET/RJ - Nova Friburgo.

## Aluno(s)

* Kevin Azevedo Perrout de Souza

## Como Executar o Projeto

Siga os passos abaixo para configurar e executar a aplicação em seu ambiente local.

**Pré-requisitos:**

* PHP 8+
* Composer
* PNPM (ou NPM/Yarn)
* Node.js (versão compatível com Vite)
* Servidor de Banco de Dados MySQL/MariaDB

**Passos:**

1.  **Clonar o Repositório (Opcional):**
    ```bash
    git clone [https://gitlab.com/cefet-nf/pis-2025-2/g8.git](https://gitlab.com/cefet-nf/pis-2025-2/g8.git)
    cd g8\p1-quiz
    ```

2.  **Configurar o Backend:**
    * Navegue até a pasta do backend:
        ```bash
        cd backend
        ```
    * Instale as dependências do PHP:
        ```bash
        composer install
        ```
    * **Crie o Banco de Dados:** Crie manualmente um banco de dados chamado `sis_avisos` no seu servidor MySQL/MariaDB com o charset `utf8mb4` e collate `utf8mb4_unicode_ci`.
        ```sql
        CREATE DATABASE IF NOT EXISTS quiz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ```
    * Execute as migrations para criar a estrutura do banco:
        ```bash
        composer migrate
        ```
    * Execute os seeders para popular o banco com dados iniciais:
        ```bash
        composer seed
        ```
    * Inicie o servidor PHP (a partir da pasta `backend/`):
        ```bash
        # Use este comando com o arquivo router.php na raiz do backend
        composer start
        ```
        ```bash
        # Use este comando com o arquivo router.php na raiz do backend
        php -S localhost:8080 -t public public/router.php
        ```
    * *O backend estará rodando em `http://localhost:8080`.*

3.  **Configurar o Frontend:**
    * Abra **outro terminal** e navegue até a pasta do frontend:
        ```bash
        cd ../frontend
        # Ou navegue diretamente para a pasta 'frontend' a partir da raiz
        ```
    * Instale as dependências do Node.js:
        ```bash
        pnpm i
        ```
    * Inicie o servidor de desenvolvimento do Vite:
        ```bash
        pnpm run dev
        ```
    * *O frontend estará acessível em `http://localhost:5173` (ou a porta indicada pelo Vite).*

4.  **Executar Controles de Qualidade:**
    * Para rodar os **testes unitários e e2e do frontend**, execute na pasta `frontend/`:
        ```bash
        pnpm run test
        ```
    * Para rodar a **análise estática do backend** (PHPStan), execute na pasta `backend/`:
        ```bash
        composer analyse
        ```
    * Para rodar os **testes no backend** (Pest), execute na pasta `backend/`:
        ```bash
        composer test
        ```
## Referências e Recursos Utilizados

* **Framework CSS:** Bootstrap 5. Utilizado para estilização rápida e responsiva da interface. Disponível em: <https://getbootstrap.com>. Acesso em: 23 out. 2025.
* **Framework Backend (Roteamento):** Slim Framework. Utilizado para gerenciamento das rotas da API RESTful. Disponível em: <https://www.slimframework.com>. Acesso em: 23 out. 2025.
* **Análise Estática PHP:** PHPStan. [cite_start]Ferramenta utilizada para encontrar erros no código PHP sem executá-lo. Disponível em: <https://phpstan.org>. Acesso em: 23 out. 2025.
* **PHPStan error:** PHPStan. Solving PHPStan error “No value type specified in iterable type”. Disponível em: <https://phpstan.org/blog/solving-phpstan-no-value-type-specified-in-iterable-type>. Acesso em: 18 nov. 2025.
* **Build Tool Frontend:** Vite. Utilizado para o desenvolvimento e build do frontend. Disponível em: <https://vitejs.dev>. Acesso em: 23 out. 2025.
* **Testes Frontend:** ViTest e Playwright. Utilizados para testes unitários e end-to-end. Disponíveis em: <https://vitest.dev> e <https://playwright.dev>. Acesso em: 23 out. 2025.
* **PHP password salt:** MEDIUM. How to Secure hash and salt for PHP passwords. Disponíveis em: <https://medium.com/@mrityunjay.webmaster/how-to-secure-hash-and-salt-for-php-passwords-54f1c9d268a6>. Acesso em: 20 nov. 2025.

* **Imagens:**
    * `portugues.jpg`: Imagem genérica de perfil Google. Disponível em: <https://lh6.googleusercontent.com/proxy/M4b_BxpqwO2H3sqJwx4YRCYwptM9B2wFEPGwt-eixTHHEqRB8E4kf3N5LK180l5ZECcXKPTYhCc-xHlxWlUG7L7rLIBRHqabYufnWUVfrB51-jC65blePfgkFCl3lhd2OzXCzpTk9g>. Acesso em: 23 out. 2025.
    * `logica.png`: Imagem promocional Evolua Profissional. Disponível em: <https://evoluaprofissional.com.br/wp-content/uploads/2016/10/Para-Web_Logica-de-Programa%C3%A7%C3%A3o.png>. Acesso em: 23 out. 2025.
    * `brasil.png`: Bandeira do Brasil via Wikimedia Commons. Disponível em: <https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Flag_of_Brazil.svg/2560px-Flag_of_Brazil.svg.png>. Acesso em: 23 out. 2025.
    * `default.png`: Ícone "No image available" via Pngtree. Disponível em: <https://png.pngtree.com/png-vector/20221125/ourmid/pngtree-no-image-available-icon-flatvector-illustration-thumbnail-graphic-illustration-vector-png-image_40966590.jpg>. Acesso em: 23 out. 2025.