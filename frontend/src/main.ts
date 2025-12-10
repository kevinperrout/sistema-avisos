import page from "page";
import { AuthModel } from "./models/AuthModel";
import { AvisoModel } from "./models/AvisoModel";
import { AvisoView } from "./views/AvisoView";
import { AvisoPresenter } from "./presenters/AvisoPresenter";
import { CadastroAvisoPresenter } from "./presenters/CadastroAvisoPresenter";
import { CadastroAvisoView } from "./views/CadastroAvisoView";
import { LayoutView } from "./views/LayoutView";
import { LoginView } from "./views/LoginView";

const root = document.getElementById("app") as HTMLElement;
const auth = new AuthModel();

LayoutView.setupEvents();
LayoutView.render(auth.getUsuario() as any);

document.addEventListener("usuarioLogado", () => {
  LayoutView.render(auth.getUsuario() as any);
  page.redirect("/avisos");
});

document.addEventListener("usuarioLogout", () => {
  LayoutView.render(null as any);
  page.redirect("/");
});

page("/avisos", async () => {
  document.body.classList.remove("mode-tv");
  root.innerHTML = '<div class="text-center mt-5"><h1>Carregando...</h1></div>';
  const presenter = new AvisoPresenter(root);
  await presenter.show(false);
});

page("/tv", async () => {
  document.body.classList.add("mode-tv");
  root.innerHTML = "";
  const presenter = new AvisoPresenter(root);
  await presenter.show(true);
});

page("/avisos/novo", () => {
  if (!auth.isAutenticado()) {
    alert("Recurso indisponível")
    page.redirect("/");
    return;
  }
  document.body.classList.remove("mode-tv");
  root.innerHTML = '';

  const presenter = new CadastroAvisoPresenter(root);
  presenter.init(); 
});

page("/", () => page.redirect("/avisos"));

page.start();
