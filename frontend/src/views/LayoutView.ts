import { Usuario } from "../models/AuthModel";
import { LoginView } from "./LoginView";
import { AuthModel } from "../models/AuthModel";

export class LayoutView {
  static render(usuario: Usuario | null) {
    const userInfo = document.getElementById("userInfo");
    const userNameDisplay = document.getElementById("userNameDisplay");
    const linkLogin = document.getElementById("linkLogin");

    if (usuario && userInfo && userNameDisplay && linkLogin) {
      userInfo.classList.remove("d-none");
      userNameDisplay.innerText = usuario.nome || "";
      linkLogin.innerText = "Sair";
      
      linkLogin.classList.replace("btn-primary", "btn-danger");
      linkLogin.setAttribute("href", "#"); 
    } else if (linkLogin && userInfo) {
      userInfo.classList.add("d-none");
      linkLogin.innerText = "Entrar";
      linkLogin.classList.replace("btn-danger", "btn-primary");
      linkLogin.setAttribute("href", "#");
    }
  }

  static setupEvents() {
    const loginView = new LoginView();
    const auth = new AuthModel();
    const linkLogin = document.getElementById("linkLogin");

    if (linkLogin) {
      linkLogin.addEventListener("click", (e) => {
        e.preventDefault();

        if (auth.isAutenticado()) {
            auth.logout(); 
        } else {
            loginView.abrir(async (email, senha) => {
                await auth.login(email, senha);
            }, "Faça seu login");
        }
      });
    }
  }
}