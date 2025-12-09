import { api } from "./Api";

export interface Usuario {
    idUsuario: number;
    nome: string;
    email: string;
}

export class UsuarioModel {
  // Lista todos os usuários
  async listar(): Promise<Usuario[]> {
    return await api.get<Usuario[]>("/usuarios");
  }
}