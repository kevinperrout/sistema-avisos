import { type Page, type Locator, expect } from '@playwright/test';

export class AdminAvisoPage {
    readonly page: Page;
    readonly btnNovoAviso: Locator;
    
    readonly inputTitulo: Locator;
    readonly inputTexto: Locator;
    readonly selectSetor: Locator;
    readonly inputValidade: Locator;
    readonly inputPublico: Locator;
    readonly checkUrgente: Locator;
    readonly btnSalvar: Locator;

    constructor(page: Page) {
        this.page = page;

        this.btnNovoAviso = page.locator('#btnNovoAviso');

        this.inputTitulo = page.locator('#titulo');
        this.inputTexto = page.locator('#texto');
        this.selectSetor = page.locator('#idSetor');
        this.inputValidade = page.locator('#dataValidade');
        this.inputPublico = page.locator('#publicoAlvo');
        this.checkUrgente = page.locator('#urgente');
        this.btnSalvar = page.locator('#btnSalvar');
    }

    async navegarParaNovoAviso() {
        if (await this.btnNovoAviso.isVisible()) {
            await this.btnNovoAviso.click();
        } else {
            await this.page.goto('/avisos/novo');
        }
        await expect(this.inputTitulo).toBeVisible();
    }

    async preencherAviso(titulo: string, texto: string) {
        await this.inputTitulo.fill(titulo);
        await this.inputTexto.fill(texto);
        
        await this.selectSetor.selectOption({ index: 1 });

        await this.inputValidade.fill('2026-04-17T17:04');

        await this.inputPublico.fill('Teste');

        const primeiroPeriodo = this.page.locator('.chk-periodo').first();
        await primeiroPeriodo.check();

        const terceiroPeriodo = this.page.locator('.chk-periodo').last();
        await terceiroPeriodo.check();
    }

    async marcarUrgente() {
        await this.checkUrgente.check();
    }

    async salvar() {
        this.page.once('dialog', async dialog => {
            console.log(`Alert diz: ${dialog.message()}`);
            await dialog.accept();
        });

        await this.btnSalvar.click();
    }
}