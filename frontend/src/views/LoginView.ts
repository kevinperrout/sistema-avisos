import { Modal } from "bootstrap";

// Exibe o modal de login do usuario antes de iniciar um quiz.
// Usa o modal padrão do Bootstrap já existente no HTML.
export class LoginView {
  private modal: Modal;
  private modalEl: HTMLElement;

  constructor() {
    this.modalEl = document.getElementById("loginModal")!;
    this.modal = new Modal(this.modalEl);
  }

// Abre o modal de login e trata o envio do formulário.
abrir(onSubmit: (email: string, senha: string) => Promise<void>, tituloPersonalizado?: string) {
    const form = this.modalEl.querySelector<HTMLFormElement>("#loginForm")!;
    const emailEl = this.modalEl.querySelector<HTMLInputElement>("#loginEmail")!;
    const senhaEl = this.modalEl.querySelector<HTMLInputElement>("#loginSenha")!;
    const erroEl = this.modalEl.querySelector<HTMLElement>("#loginErro")!;
    const tituloEl = this.modalEl.querySelector<HTMLElement>(".modal-title");

    emailEl.value = "";
    senhaEl.value = "";
    erroEl.classList.add("d-none");

    if (tituloEl) {
        tituloEl.textContent = tituloPersonalizado || "Identifique-se para iniciar o Quiz";
    }

    form.onsubmit = async (e) => {
      e.preventDefault();
      erroEl.classList.add("d-none");

      const email = emailEl.value.trim();
      const senha = senhaEl.value.trim();

      if (!email || !senha) {
        erroEl.textContent = "E-mail e senha são obrigatórios.";
        erroEl.classList.remove("d-none");
        return;
      }
      
      try {
        await onSubmit(email, senha); 
        this.modal.hide();

      } catch (err) {
        console.error("Falha no login:", err);

        erroEl.textContent = "E-mail ou senha inválidos.";
        erroEl.classList.remove("d-none");
      }
    };

    this.modal.show();
    setTimeout(() => emailEl.focus(), 100);
  }
}