import { type Page, type Locator, expect } from '@playwright/test';

export class AvisosPage {
    readonly page: Page;
    readonly aviso: Locator;

    constructor(page: Page) {
        this.page = page;
        
        this.aviso = page.locator('.aviso').first();
    }

    async visitar() {
        await this.page.goto('/avisos');
        await expect(this.aviso).toBeVisible({ timeout: 5000 });
    }
    
    async clicaraviso() {
        await this.aviso.click();
    }
}