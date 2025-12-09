import { api } from "./Api";

export interface Setor {
  idSetor: number;
  nome: string;
  cor: string;
}

export interface Periodo {
  idPeriodo: number;
  nome: string;
  inicio: string;
  fim: string;
}

export class OpcoesModel {
  
  async listarSetores(): Promise<Setor[]> {
    const res = await api.get<{ setores: Setor[] }>("/setores");
    return res.setores || [];
  }

  async listarPeriodos(): Promise<Periodo[]> {
    const res = await api.get<{ periodos: Periodo[] }>("/periodos");
    return res.periodos || [];
  }
}