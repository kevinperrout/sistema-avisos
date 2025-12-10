import { test } from '@playwright/test';
import { LoginPage } from './pom/LoginPage';
import { AdminAvisoPage } from './pom/AdminAvisoPage';
import { TvPage } from './pom/TvPage';

test.describe('Teste de anuncio na tv', () => {

    test('Aviso criado tem que aparecer na resposta da api', async ({ page }) => {
        const loginPage = new LoginPage(page);
        const adminPage = new AdminAvisoPage(page);
        const tvPage = new TvPage(page);
        
        const tituloTeste = "Teste TV";

        await page.goto('/');
        await loginPage.abrirModalSeNecessario();
        await loginPage.fazerLogin('admin@acme.br', '123456');

        await loginPage.verificarLoginSucesso(); 

        await adminPage.navegarParaNovoAviso();
        await adminPage.preencherAviso(tituloTeste, 'Este aviso é um teste, desconsiderar.');
        await adminPage.marcarUrgente();
        await adminPage.salvar();

        await tvPage.visitar();
        await tvPage.verificarSeAvisoExiste(tituloTeste);
    });
});