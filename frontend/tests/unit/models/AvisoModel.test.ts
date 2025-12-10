import { describe, it, expect, beforeEach, vi } from "vitest";
import { AvisoModel } from "../../../src/models/AvisoModel";

beforeEach(() => {
  vi.restoreAllMocks();
  global.fetch = vi.fn();
});

describe("AvisoModel (Lógica de Negócio)", () => {
  const model = new AvisoModel();

  it("listarAvisos() deve retornar uma lista de avisos quando a API responde", async () => {
    const avisoMock = [
      {
        idAviso: 1,
        titulo: "Dragon Ball Super - Final do torneio do poder",
        texto: "O final do Torneio do Poder...",
        urgente: false,
        dataHoraValidade: "2025-12-18 17:25:52",
        nomeSetor: "Assuntos gerais",
        corSetor: "#7aa30a",
        periodos: ["Manhã", "Noite"],
        publicoAlvo: "Todos",
        nomeAutor: "Administrador",
        dataCriacao: "2025-12-10 09:44:20",
      },
    ];

    (global.fetch as any).mockResolvedValue({
      ok: true,
      json: async () => avisoMock,
    });

    const resultado = await model.listarAvisos();

    expect(resultado[0].titulo).toContain("Dragon Ball Super");
    expect(resultado[0].nomeSetor).toBe("Assuntos gerais");
    expect(resultado[0].periodos).toContain("Manhã");
  });

  it("listarAvisos() deve retornar array vazio se der erro na API", async () => {
    (global.fetch as any).mockRejectedValue(new Error("Erro de rede"));

    const resultado = await model.listarAvisos();
    expect(resultado).toEqual([]);
  });

  it("cadastrar() deve enviar o objeto corretamente via POST", async () => {
    const dadosNovoAviso = {
      titulo: "Aviso",
      texto: "Texto do aviso",
      urgente: false,
      dataHoraValidade: "2026-04-17T10:42",
      idSetor: 1,
      publicoAlvo: "Todos",
      periodos: [1, 2],
    };

    (global.fetch as any).mockResolvedValue({
      ok: true,
      json: async () => ({ id: 50, mensagem: "Sucesso" }),
    });

    await model.cadastrar(dadosNovoAviso as any);

    expect(global.fetch).toHaveBeenCalledWith(
      expect.stringContaining("avisos"),
      expect.objectContaining({
        method: "POST",
        body: expect.stringContaining("Aviso"),
      })
    );
  });
});
