import { type Page, type Locator, expect } from '@playwright/test';

export class TvPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async visitar() {
        await this.page.goto('/tv');
    }

    async verificarSeAvisoExiste(tituloEsperado: string) {
        const responsePromise = this.page.waitForResponse(response => 
            response.url().includes('/api/avisos') && response.status() === 200
        );
        
        const response = await responsePromise;
        const json = await response.json();

        const avisoEncontrado = json.find((a: any) => a.titulo === tituloEsperado);
        
        expect(avisoEncontrado).toBeTruthy();
    }
}