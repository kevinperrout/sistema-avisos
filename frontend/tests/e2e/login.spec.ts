import { test, expect } from '@playwright/test';
import { LoginPage } from './pom/LoginPage';

test.describe('Teste de login', () => {
    let loginPage: LoginPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        await page.goto('/');
        await loginPage.abrirModalSeNecessario();
    });

    test('Deve exibir erro ao tentar logar com credenciais inválidas', async () => {
        await loginPage.fazerLogin('admin@acme.br', 'senha_errada');
        await loginPage.verificarLoginErro();
    });

    test('Deve realizar login com sucesso e fechar o modal', async () => {
        await loginPage.fazerLogin('admin@acme.br', '123456');
        await loginPage.verificarLoginSucesso();
    });

test('Deve proteger a rota de cadastro (bloquear acesso não logado)', async ({ page }) => {
        await page.goto('/avisos/novo');

        await expect(page).not.toHaveURL(/\/avisos\/novo/);

        const loginPage = new LoginPage(page);
        await loginPage.abrirModalSeNecessario();
        await expect(page.locator('#loginModal')).toBeVisible();
    });
});