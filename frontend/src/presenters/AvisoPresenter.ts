import { AvisoModel, Aviso } from "../models/AvisoModel";
import { AvisoView } from "../views/AvisoView";
import { LoginView } from "../views/LoginView";
import { AuthModel } from "../models/AuthModel";

export class AvisoPresenter {
  private model: AvisoModel;
  private view: AvisoView;
  private auth: AuthModel;
  private loginView: LoginView;

  private filaDeAvisos: Aviso[] = [];
  private totalAvisos: number = 0;
  private intervaloTimer: any = null;
  private intervaloBusca: any = null;

  private readonly TEMPO_ROTACAO = 60;
  private readonly TEMPO_BUSCA = 10;
  private tempoAtual = 60;

  constructor(
    private root: HTMLElement,
    model?: AvisoModel,
    view?: AvisoView,
    auth?: AuthModel,
    loginView?: LoginView
  ) {
    this.model = model ?? new AvisoModel();
    this.view = view ?? new AvisoView(this.root);
    this.auth = auth ?? new AuthModel();
    this.loginView = loginView ?? new LoginView();
  }

  async show(tvMode: boolean = false) {
    this.pararTimers();

    const usuarioBadge = document.getElementById("usuarioBadge");
    if (!tvMode) {
      const usuario = this.auth.getUsuario();
      document.body.classList.remove("mode-tv");
      if (usuarioBadge) {
        if (usuario) {
          usuarioBadge.textContent = usuario.nome
            ? `${usuario.nome} (${usuario.email})`
            : usuario.email;
          usuarioBadge.classList.remove("d-none");
        } else {
          usuarioBadge.classList.add("d-none");
        }
      }

      const avisos = await this.model.listarAvisos();
      this.view.render(avisos, false, this.TEMPO_ROTACAO);
    } else {
      if (usuarioBadge) usuarioBadge.classList.add("d-none");
      document.body.classList.add("mode-tv");
      await this.iniciarCicloTv();
    }
  }

  private async iniciarCicloTv() {
    await this.atualizarFila();
    this.tempoAtual = this.TEMPO_ROTACAO;

    this.view.render(this.filaDeAvisos, true, this.TEMPO_ROTACAO);

    this.intervaloTimer = setInterval(() => {
      this.tick();
    }, 1000);

    this.intervaloBusca = setInterval(async () => {
      await this.atualizarFila();
    }, this.TEMPO_BUSCA * 1000);
  }

  private tick() {
    this.tempoAtual--;

    let maxNaTela = 3;
    if (window.innerHeight > 850) maxNaTela = 5;
    else if (window.innerHeight < 600) maxNaTela = 2;

    const exibidosReal = Math.min(this.filaDeAvisos.length, maxNaTela);

    this.view.atualizarStatusTv(
      exibidosReal,
      this.filaDeAvisos.length,
      this.tempoAtual,
      this.TEMPO_ROTACAO
    );

    if (this.tempoAtual <= 0) {
      this.rotacionarFila();
      this.tempoAtual = this.TEMPO_ROTACAO;
    }
  }

  private rotacionarFila() {
    if (this.filaDeAvisos.length > 1) {
      const item = this.filaDeAvisos.shift();
      if (item) this.filaDeAvisos.push(item);

      this.view.render(this.filaDeAvisos, true, this.TEMPO_ROTACAO);
    }
  }

  private async atualizarFila() {
    try {
      const todos = await this.model.listarAvisos();

      this.totalAvisos = todos.length;

      const validosAgora = todos.filter((aviso) =>
        this.ehAvisoValidoParaAgora(aviso)
      );

      if (this.filaDeAvisos.length === 0) {
        this.filaDeAvisos = validosAgora;

        if (!this.intervaloTimer) {
          this.view.render(this.filaDeAvisos, true, this.totalAvisos);
        }
      } else {
        validosAgora.forEach((novoAviso) => {
          const index = this.filaDeAvisos.findIndex(
            (a) => a.idAviso === novoAviso.idAviso
          );

          if (index >= 0) {
            this.filaDeAvisos[index] = novoAviso;
          } else {
            this.filaDeAvisos.push(novoAviso);
          }
        });

        this.filaDeAvisos = this.filaDeAvisos.filter((antigo) =>
          validosAgora.some((v) => v.idAviso === antigo.idAviso)
        );
      }
    } catch (e) {
      console.error("Erro ao buscar avisos:", e);
    }
  }

  private ehAvisoValidoParaAgora(aviso: Aviso): boolean {
    const agora = new Date();

    const validade = new Date(aviso.dataHoraValidade);
    if (agora > validade) return false;

    if (!aviso.periodos || aviso.periodos.length === 0) return true;

    const hora = agora.getHours();
    let periodoAtual = "";

    if (hora >= 0 && hora < 13) periodoAtual = "Manhã";
    else if (hora >= 13 && hora < 18) periodoAtual = "Tarde";
    else periodoAtual = "Noite";

    const periodosNormalizados = aviso.periodos.map((p) =>
      p
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
    );
    const atualNormalizado = periodoAtual
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");

    return periodosNormalizados.includes(atualNormalizado);
  }

  private pararTimers() {
    if (this.intervaloTimer) clearInterval(this.intervaloTimer);
    if (this.intervaloBusca) clearInterval(this.intervaloBusca);
    this.intervaloTimer = null;
    this.intervaloBusca = null;
    this.filaDeAvisos = [];
  }
}
