import type { Aviso } from "../models/AvisoModel";

export class AvisoView {
  constructor(private root: HTMLElement) {}

  render(avisos: Aviso[], tvMode: boolean = false, totalSegundos: number = 0) {
    let maxItens = 3;
    if (tvMode) {
      const altura = window.innerHeight;
      if (altura > 950) maxItens = 5;
      else if (altura > 900) maxItens = 4;
      else if (altura > 750) maxItens = 3;
      else if (altura > 630) maxItens = 2;
      else if (altura < 630) maxItens = 1;
    }

    const listaFinal = tvMode ? avisos.slice(0, maxItens) : avisos;
    const usuarioLogado = localStorage.getItem("acme.usuario");

    const overflowClass = tvMode ? "overflow-hidden" : "overflow-auto";

    const containerClasses = `container-fluid vh-100 ${overflowClass} d-flex flex-column bg-light text-black p-4`;
    const gridClasses = tvMode
      ? "d-flex flex-column flex-grow-1 gap-3 h-100"
      : "row g-2 flex-grow-1 overflow-y-auto pb-5 align-content-start";

    if (tvMode) this.root.classList.add("mode-tv");
    else this.root.classList.remove("mode-tv");

    this.root.innerHTML = `
      <div class="${containerClasses}">
        
        <div class="d-flex justify-content-between align-items-center mb-3 ${
          tvMode ? "border-bottom border-secondary pb-2" : ""
        }">
          <h2 class="m-0">${
            tvMode ? "Quadro de Avisos" : "Listagem de Avisos"
          }</h2>
          
          ${
            !tvMode && usuarioLogado
              ? `<a href="/avisos/novo" class="btn btn-success" id="btnNovoAviso">Novo aviso</a>`
              : ""
          }

          ${
            tvMode
              ? `
             <div class="text-end" style="min-width: 200px;">
                <p class="text-muted">Exibindo:
                    ${listaFinal.length} de ${avisos.length}
                </p>
                
                <div class="d-flex align-items-center gap-2 mt-1">
                    <div class="progress flex-grow-1" style="height: 6px;">
                        <div id="tvBarra" class="progress-bar bg-primary transition-none" style="width: 100%"></div>
                    </div>
                    <small id="tvTimer" class="text-muted fw-bold" style="width: 30px;">${totalSegundos}</small>
                </div>
             </div>
          `
              : ""
          }
        </div>

        <div class="${gridClasses}" id="gridAvisos">
          ${
            listaFinal.length > 0
              ? listaFinal
                  .map((aviso, index) => this.renderCard(aviso, tvMode, index))
                  .join("")
              : '<div class="col-12 text-center text-muted mt-5">Nenhum aviso ativo no momento.</div>'
          }
        </div>
      </div>
    `;
  }

  atualizarStatusTv(
    exibidos: number,
    total: number,
    segundosRestantes: number,
    totalSegundos: number
  ) {
    const contador = document.getElementById("tvContador");
    const barra = document.getElementById("tvBarra");
    const timer = document.getElementById("tvTimer");

    if (contador) contador.textContent = `${exibidos} de ${total}`;

    if (timer) timer.textContent = `${segundosRestantes}s`;

    if (barra) {
      const porcentagem = (segundosRestantes / totalSegundos) * 100;
      barra.style.width = `${porcentagem}%`;

      if (porcentagem < 20) {
        barra.className = "progress-bar bg-danger";
      } else {
        barra.className = "progress-bar bg-primary";
      }
    }
  }

  private renderCard(aviso: Aviso, tvMode: boolean, index: number): string {
    const {
      titulo,
      texto,
      urgente,
      nomeSetor,
      nomeAutor,
      publicoAlvo,
      dataHoraValidade,
    } = aviso;

    const cor = aviso.corSetor;
    const periodos = aviso.periodos?.join(", ");
    const validade = new Date(dataHoraValidade).toLocaleDateString("pt-BR");

    const isTvList = tvMode && index > 0;
    const isHero = tvMode && index === 0;

    let wrapperClass = "col-12 col-md-6 col-lg-4";
    if (tvMode) {
      wrapperClass = isHero
        ? "flex-grow-1 w-100 overflow-hidden"
        : "flex-shrink-0 w-100 opacity-75";
    }

    const bgBase = isTvList ? "bg-light" : "bg-white";
    const bgUrgente = isTvList
      ? "bg-danger bg-opacity-25"
      : "bg-danger bg-opacity-10";
    const bgClass = urgente ? bgUrgente : bgBase;

    let cardClass = "h-100 shadow-sm";
    let titleClass = "fw-bold card-title";
    let borderSize = "5px";
    let showText = true;
    let showFooter = true;
    let inlineStyle = "";

    if (tvMode) {
      if (isHero) {
        cardClass = `h-100 shadow-lg border-0 ${
          urgente ? "animate__animated animate__pulse animate__infinite" : ""
        }`;
        titleClass = "display-6 fw-bold mb-2 lh-1";
        borderSize = "25px";
        inlineStyle = "height: 40vh; min-height: 400px;";
      } else {
        const borderExtra = urgente ? "border-danger" : "border-secondary";
        cardClass = `p-2 border ${borderExtra}`;
        titleClass = "h5 m-0 fw-normal";
        borderSize = "10px";
        showText = false;
        showFooter = false;
      }
    }

    const badgeUrgente = urgente
      ? `<span class="badge bg-danger ms-2 ${
          tvMode ? "animate__animated animate__flash animate__infinite" : ""
        }">URGENTE</span>`
      : "";

    return `
      <div class="${wrapperClass}" style="transition: all 0.5s ease; ${inlineStyle}">
        <div class="card ${cardClass} ${bgClass} text-black" style="border-left: ${borderSize} solid ${cor} !important;">
          <div class="card-body d-flex flex-column ${
            isTvList ? "justify-content-center" : ""
          }">
            
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge" style="background-color: ${cor}">${nomeSetor}</span>
                ${badgeUrgente}
            </div>

            <h5 class="${titleClass}">${titulo}</h5>
            
            ${
              showText
                ? `
                <p class="card-text mt-3 flex-grow-1 ${
                  isHero ? "fs-3 lh-sm" : ""
                }" style="white-space: pre-wrap;">${texto}</p>
            `
                : ""
            }
            
            ${
              showFooter
                ? `
                <div class="mt-2 pt-1 border-top small text-muted">
                   <div class="mb-1"><i class="bi bi-people"></i> <strong>Público:</strong> ${publicoAlvo}</div>
                   <div class="mb-1"><i class="bi bi-calendar"></i> <strong>Validade:</strong> ${validade}</div>
                   
                   <div class="d-flex justify-content-between align-items-end mt-2">
                      <span><strong>Exibição:</strong> ${periodos}</span>
                      <span class="mb-1"><strong>Autor:</strong> ${nomeAutor}</span>
                   </div>
                </div>
            `
                : ""
            }

          </div>
        </div>
      </div>
    `;
  }
}
