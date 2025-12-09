import { API_BASE } from "./Api";

// Define o formato dos dados do usuario.
export interface Usuario {
  idUsuario: number;
  nome?: string;
  email: string;
}

// Modelo responsável por autenticação e sessão do usuario.
export class AuthModel {
  private storageKey = "acme.usuario";

  // Faz login do usuario e salva os dados localmente.
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

    // Cria objeto usuario
    const usuario: Usuario = {
      idUsuario: dadosDoBackend.idUsuario,
      email: dadosDoBackend.email,
      nome: dadosDoBackend.nome,
    };

    // Salva só o objeto Usuario
    localStorage.setItem(this.storageKey, JSON.stringify(usuario));
    document.dispatchEvent(new Event("usuarioLogado"));

    // Retorna só objeto Usuario
    return usuario;
  }

  // Encerra a sessão removendo o usuario do localStorage.
  logout(): void {
    localStorage.removeItem(this.storageKey);
    document.dispatchEvent(new Event("usuarioLogout"));
  }

  // Retorna o usuario atualmente logado, ou null se não houver.
  getUsuario(): Usuario | null {
    const raw = localStorage.getItem(this.storageKey);
    return raw ? (JSON.parse(raw) as Usuario) : null;
  }

  // Retorna true se há um usuario logado no momento.
  isAutenticado(): boolean {
    return !!this.getUsuario();
  }
}
