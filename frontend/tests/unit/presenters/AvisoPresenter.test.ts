import { describe, it, expect, vi, beforeEach } from 'vitest';
import { AvisoView } from '../../../src/views/AvisoView';
import { AvisoPresenter } from '../../../src/presenters/AvisoPresenter';

vi.mock('../../../src/views/LoginView', () => {
    return {
        LoginView: vi.fn().mockImplementation(() => {
            return {
            };
        })
    };
});

// bootstrap mockado pq JSDOM nãoo carrega os scripts do bootstrap.bundle.js
// pra evitar erro de backdrop ao tentar abrir o modal.
vi.mock('bootstrap', () => {
    return {
        Modal: vi.fn().mockImplementation(() => {
            return {
                show: vi.fn(),
                hide: vi.fn()
            };
        })
    };
});

document.body.innerHTML = '<div id="app"></div>';
const root = document.getElementById('app') as HTMLElement;

describe('AvisoPresenter & View', () => {

    beforeEach(() => {
        root.innerHTML = '';
        root.className = '';
        vi.restoreAllMocks();
        global.fetch = vi.fn();
    });

    it('View deve renderizar estrutura básica do Quadro de Avisos', () => {
        const view = new AvisoView(root);
        view.render([], false); 

        const html = root.innerHTML;
        expect(html).toContain('Listagem de Avisos');
    });

    it('View deve renderizar corretamente o "Modo TV"', () => {
        const view = new AvisoView(root);
        
        view.render([], true);

        expect(root.classList.contains('mode-tv')).toBe(true);
        expect(root.innerHTML).toContain('Quadro de Avisos');
    });

    it('Presenter deve buscar avisos e atualizar a View', async () => {
        const avisosMock = [
            { idAviso: 1, titulo: 'Aviso Importante', texto: 'Teste', urgente: true, periodos: ['Manhã'], dataHoraValidade: '2030-01-01' }
        ];

        (global.fetch as any).mockResolvedValue({
            ok: true,
            json: async () => (avisosMock)
        });

        const view = new AvisoView(root);
        
        const presenter = new AvisoPresenter(root, undefined, view); 
        
        const spyRender = vi.spyOn(view, 'render');

        await presenter.show(false);

        expect(global.fetch).toHaveBeenCalledWith(expect.stringContaining('/api/avisos'), expect.anything());

        expect(spyRender).toHaveBeenCalledWith(
            expect.arrayContaining([expect.objectContaining({ titulo: 'Aviso Importante' })]),
            expect.anything(),
            expect.anything()
        );
        
        expect(root.innerHTML).toContain('Aviso Importante');
    });
});