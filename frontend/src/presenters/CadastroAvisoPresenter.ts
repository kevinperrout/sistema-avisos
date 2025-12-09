import { CadastroAvisoView } from '../views/CadastroAvisoView';
import { AvisoModel } from '../models/AvisoModel';
import page from 'page';

export class CadastroAvisoPresenter {
    private view: CadastroAvisoView;
    private model: AvisoModel;

    constructor(root: HTMLElement) {
        this.view = new CadastroAvisoView(root);
        this.model = new AvisoModel();
    }

    public init(): void {
        this.view.render(async (dados) => {
            await this.processarCadastro(dados);
        });
    }

    private async processarCadastro(dados: any) {
        try {
            await this.model.cadastrar(dados);
            
            this.view.mostrarSucesso();
            
            page.redirect('/avisos');
            
        } catch (error: any) {
            this.view.mostrarErro(error.message || 'Erro ao cadastrar.');
        }
    }
}