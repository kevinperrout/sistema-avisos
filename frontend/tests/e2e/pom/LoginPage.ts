import { type Page, type Locator, expect } from "@playwright/test";

export class LoginPage {
  readonly page: Page;
  readonly modal: Locator;
  readonly campoEmail: Locator;
  readonly campoSenha: Locator;
  readonly botaoEntrar: Locator;
  readonly msgErro: Locator;

  constructor(page: Page) {
    this.page = page;

    this.modal = page.locator("#loginModal");
    this.campoEmail = page.locator("#loginEmail");
    this.campoSenha = page.locator("#loginSenha");
    this.botaoEntrar = page.locator("#loginForm button[type=submit]");
    this.msgErro = page.locator("#loginErro");
  }

  async abrirModalSeNecessario() {
    if (!(await this.modal.isVisible())) {
      await this.page.locator("text=Entrar").first().click();
    }
  }

  async fazerLogin(email: string, senha: string) {
    await expect(this.modal).toBeVisible();
    await this.campoEmail.fill(email);
    await this.campoSenha.fill(senha);
    await this.botaoEntrar.click();
  }

  async verificarLoginSucesso() {
    await expect(this.modal).not.toBeVisible();

    const userName = this.page.locator("#userNameDisplay");
    await expect(userName).toBeVisible();
  }

  async verificarLoginErro() {
    await expect(this.msgErro).toBeVisible();
    await expect(this.msgErro).toContainText("inválidos");
  }
}
