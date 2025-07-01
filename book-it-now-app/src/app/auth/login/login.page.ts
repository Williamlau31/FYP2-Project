import type { Router } from "@angular/router"
import type { ToastController } from "@ionic/angular/standalone"
import type { AuthService } from "../../shared/auth.service"

export default class LoginPage {
  email = ""
  password = ""
  isLoading = false

  constructor(
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController,
  ) {}

  async onLogin() {
    if (!this.email || !this.password) {
      this.showToast("Please enter email and password", "warning")
      return
    }

    this.isLoading = true

    this.authService.login(this.email, this.password).subscribe({
      next: (response) => {
        this.isLoading = false
        if (response) {
          this.showToast("Login successful!", "success")
          this.router.navigate(["/home"])
        } else {
          this.showToast("Invalid credentials", "danger")
        }
      },
      error: (error) => {
        this.isLoading = false
        console.error("Login error:", error)
        this.showToast("Login failed. Please try again.", "danger")
      },
    })
  }

  async showToast(message: string, color = "primary") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }
}
