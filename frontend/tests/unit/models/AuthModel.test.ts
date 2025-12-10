import { describe, it, expect, beforeEach, vi } from 'vitest';
import { AuthModel } from '../../../src/models/AuthModel';

global.fetch = vi.fn();

beforeEach(() => {
    localStorage.clear();
    vi.restoreAllMocks();
});

describe('Teste de autenticação', () => {

    it('Após autenticação, deve salvar acme.usuario no localStorage', async () => {
        const model = new AuthModel();
        
        const fakeResponse = {
            idUsuario: 1,
            nome: 'Admin',
            email: 'admin@acme.br'
        };

        (global.fetch as any).mockResolvedValue({
            ok: true,
            json: async () => fakeResponse
        });

        const result = await model.login('admin@acme.br', '123456');

        expect(result.email).toBe('admin@acme.br');
        expect(result.idUsuario).toBe(1);

        const salvo = localStorage.getItem('acme.usuario');
        expect(salvo).not.toBeNull();
        expect(salvo).toContain('"idUsuario":1');
    });

    it('login() deve lançar erro se a senha estiver errada', async () => {
        const model = new AuthModel();

        (global.fetch as any).mockResolvedValue({
            ok: false,
            status: 401,
            statusText: 'Unauthorized'
        });

        await expect(model.login('admin@acme.br', 'senha_errada'))
            .rejects.toThrow();
        
        expect(localStorage.getItem('acme.usuario')).toBeNull();
    });

    it('logout() deve limpar o localStorage', () => {
        localStorage.setItem('acme.usuario', JSON.stringify({ idUsuario: 1 }));
        
        const model = new AuthModel();
        model.logout();

        expect(localStorage.getItem('acme.usuario')).toBeNull();
    });
});