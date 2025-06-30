import { Component } from "@angular/core"
import type { Router } from "@angular/router"
import type { ToastController } from "@ionic/angular"
import type { AuthService, LoginCredentials } from "../../auth.service"

@Component({
  selector: "app-login",
  templateUrl: "./login.page.html",
  styleUrls: ["./login.page.scss"],
})
export default class LoginPage {
  credentials: LoginCredentials = {
    email: "",
    password: "",
  }
  loading = false

  constructor(
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController,
  ) {}

  async presentToast(message: string, color = "success") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }

  onSubmit() {
    if (!this.credentials.email || !this.credentials.password) {
      this.presentToast("Please fill in all fields", "danger")
      return
    }

    this.loading = true
    this.authService.login(this.credentials).subscribe({
      next: (response) => {
        this.loading = false
        this.presentToast(`Welcome ${response.user.name}!`, "success")
        this.router.navigate(["/home"])
      },
      error: (error) => {
        this.loading = false
        console.error("Login error:", error)

        if (error.status === 401) {
          this.presentToast("Invalid email or password", "danger")
        } else if (error.status === 422) {
          this.presentToast("Please check your input", "danger")
        } else {
          this.presentToast("Login failed. Please try again.", "danger")
        }
      },
    })
  }

  loginAsAdmin() {
    this.loading = true
    this.credentials = { email: "admin@bookitnow.com", password: "admin123" }
    this.onSubmit()
  }

  loginAsUser() {
    this.loading = true
    this.credentials = { email: "user@bookitnow.com", password: "user123" }
    this.onSubmit()
  }
}
