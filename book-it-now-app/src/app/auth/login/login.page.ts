import { Component } from "@angular/core"
import type { Router } from "@angular/router"
import type { ToastController } from "@ionic/angular"
import type { AuthService } from "../../shared/auth.service"

@Component({
  selector: "app-login",
  templateUrl: "./login.page.html",
  styleUrls: ["./login.page.scss"],
})
export default class LoginPage {
  email = ""
  password = ""

  constructor(
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController,
  ) {}

  async onLogin() {
    if (this.authService.login(this.email, this.password)) {
      const toast = await this.toastController.create({
        message: "Login successful!",
        duration: 2000,
        color: "success",
      })
      toast.present()
      this.router.navigate(["/home"])
    } else {
      const toast = await this.toastController.create({
        message: "Invalid credentials",
        duration: 2000,
        color: "danger",
      })
      toast.present()
    }
  }
}
