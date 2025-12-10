import { test, expect } from '@playwright/test';
import { LoginPage } from './pom/LoginPage';
import { AdminAvisoPage } from './pom/AdminAvisoPage';

test.describe('Testes de cadastro de aviso', () => {
    let loginPage: LoginPage;
    let adminPage: AdminAvisoPage;

    test.beforeEach(async ({ page }) => {
        loginPage = new LoginPage(page);
        adminPage = new AdminAvisoPage(page);

        await page.goto('/');
        await loginPage.abrirModalSeNecessario();
        await loginPage.fazerLogin('admin@acme.br', '123456');
        await loginPage.verificarLoginSucesso();
    });

    test('Deve preencher e salvar um novo aviso corretamente', async () => {
        const tituloTeste = "Titulo teste";

        await adminPage.navegarParaNovoAviso();
        
        await adminPage.preencherAviso(tituloTeste, 'Teste de cadastro isolado.');
        await adminPage.marcarUrgente();
        
        await adminPage.salvar();
        
        await expect(adminPage.page).toHaveURL(/\/avisos/);
    });
});