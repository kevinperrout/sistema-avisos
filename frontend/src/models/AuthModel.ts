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

    const res = await fetch('http://localhost:8080' + API_BASE + urlLogin, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: credenciais,
      credentials: "include"
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

    localStorage.setItem(this.storageKey, JSON.stringify(usuario));
    document.dispatchEvent(new Event("usuarioLogado"));

    return usuario;
  }

  logout(): void {
    localStorage.removeItem(this.storageKey);
    document.dispatchEvent(new Event("usuarioLogout"));
  }

  getUsuario(): Usuario | null {
    const raw = localStorage.getItem(this.storageKey);
    return raw ? (JSON.parse(raw) as Usuario) : null;
  }

  isAutenticado(): boolean {
    return !!this.getUsuario();
  }
}
