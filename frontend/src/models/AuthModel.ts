import { API_BASE } from "./Api";

export interface Usuario {
  idUsuario: number;
  nome?: string;
  email: string;
}

export class AuthModel {
  private storageKey = "acme.usuario";

  async login(email: string, senha: string): Promise<Usuario> {
    const credenciais = JSON.stringify({ email, senha });
    const urlLogin = "/login";

    const res = await fetch("http://localhost:8080" + API_BASE + urlLogin, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: credenciais,
      credentials: "include",
    });

    if (!res.ok) {
      throw new Error("Login inválido");
    }

    const dadosDoBackend: any = await res.json();

    if (dadosDoBackend.erro) {
      throw new Error(dadosDoBackend.erro);
    }

    if (!dadosDoBackend.idUsuario && !dadosDoBackend.id) {
      throw new Error("Resposta inválida do servidor. Sem ID.");
    }

    const usuario: Usuario = {
      idUsuario: dadosDoBackend.idUsuario,
      email: dadosDoBackend.email,
      nome: dadosDoBackend.nome,
    };

    this.salvarSessaoSegura(usuario);
    document.dispatchEvent(new Event("usuarioLogado"));

    return usuario;
  }

  logout(): void {
    localStorage.removeItem(this.storageKey);
    document.dispatchEvent(new Event("usuarioLogout"));
  }

  getUsuario(): Usuario | null {
    const raw = localStorage.getItem(this.storageKey);
    if (!raw) return null;

    try {
      const jsonString = atob(raw);
      return JSON.parse(jsonString) as Usuario;
    } catch (error) {
      console.error("Erro ao decodificar sessão:", error);
      this.logout();
      return null;
    }
  }

  isAutenticado(): boolean {
    return !!this.getUsuario();
  }

  private salvarSessaoSegura(usuario: Usuario): void {
    const jsonString = JSON.stringify(usuario);
    // btoa() converte string para Base64
    const base64 = btoa(jsonString);
    localStorage.setItem(this.storageKey, base64);
  }
}
