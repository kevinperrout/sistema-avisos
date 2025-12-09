import { api } from "./Api";

export interface Aviso {
  idAviso?: number;
  titulo: string;
  texto: string;
  urgente: boolean;
  dataHoraValidade: string;
  nomeSetor?: string;
  corSetor?: string;
  periodos: string[];
  publicoAlvo: string;
  nomeAutor?: string;
  dataCriacao: string;
}

export interface Setor {
  idSetor: number;
  nome: string;
  cor: string;
  created_at: string;
}

export interface Periodo {
  idPeriodo: number;
  nome: string;
  horario_inicio: string;
  horario_fim: string;
  created_at: string;
}

export class AvisoModel {
  async listarAvisos(): Promise<Aviso[]> {
    const response = await api.get<Aviso[]>("/avisos");
    return response || [];
  }

  async listarSetores(): Promise<Setor[]> {
    const setores = await api.get<Setor[]>("/setores");
    return setores || [];
  }

  async listarPeriodos(): Promise<Periodo[]> {
    const periodos = await api.get<Periodo[]>("/periodos");
    return periodos || [];
  }

  async cadastrar(aviso: Aviso): Promise<void> {
    await api.post("/avisos", aviso);
  }

  async excluir(idAviso: number): Promise<void> {
    await api.post(`/avisos/${idAviso}/delete`);
  }
}
