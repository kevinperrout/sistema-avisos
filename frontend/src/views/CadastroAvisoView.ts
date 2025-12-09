import { AvisoModel } from "../models/AvisoModel";

export class CadastroAvisoView {
    private app: HTMLElement;
    private model = new AvisoModel();

    constructor(private root: HTMLElement) {
        this.app = root;
    }

    async render(onSubmit: (dados: any) => Promise<void>) {
        const setores = await this.model.listarSetores();
        const periodos = await this.model.listarPeriodos();

        this.app.innerHTML = `
            <div class="container mt-4" style="max-width: 1400px;">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="m-0">Novo aviso</h4>
                    </div>
                    <div class="card-body">
                        <form id="form-cadastro">
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" id="titulo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texto</label>
                                <textarea id="texto" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Setor</label>
                                    <select id="idSetor" class="form-select" required>
                                        <option value="">Selecione...</option>
                                        ${setores.map(s => `<option value="${s.idSetor}">${s.nome}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Validade</label>
                                    <input type="datetime-local" id="dataValidade" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Público Alvo</label>
                                <input type="text" id="publicoAlvo" class="form-control" list="publicoList" required>
                                <datalist id="publicoList">
                                    <option value="Todos">
                                    <option value="Administrativos">
                                    <option value="Alunos">
                                    <option value="Funcionários">
                                    <option value="Professores">
                                    <option value="Técnicos">
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label class="d-block mb-2">Exibir nos períodos:</label>
                                ${periodos.map(p => `
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input chk-periodo" type="checkbox" value="${p.idPeriodo}" id="p${p.idPeriodo}">
                                        <label class="form-check-label" for="p${p.idPeriodo}">${p.nome}</label>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="urgente">
                                <label class="form-check-label text-danger fw-bold" for="urgente">URGENTE</label>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Salvar aviso</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
        
        const form = document.getElementById('form-cadastro');
        form?.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            const checks = document.querySelectorAll('.chk-periodo:checked');
            const idsPeriodos = Array.from(checks).map((c: any) => Number(c.value));

            if (idsPeriodos.length === 0) {
                alert("Selecione ao menos um período.");
                return;
            }

            const dados = {
                titulo: (document.getElementById('titulo') as HTMLInputElement).value,
                texto: (document.getElementById('texto') as HTMLInputElement).value,
                urgente: (document.getElementById('urgente') as HTMLInputElement).checked,
                dataValidade: (document.getElementById('dataValidade') as HTMLInputElement).value,
                idSetor: Number((document.getElementById('idSetor') as HTMLSelectElement).value),
                publicoAlvo: (document.getElementById('publicoAlvo') as HTMLInputElement).value,
                idsPeriodos: idsPeriodos
            };

            await onSubmit(dados);
        });
    }

    public mostrarSucesso() { alert('Sucesso!'); }
    public mostrarErro(msg: string) { alert('Erro: ' + msg); }
}