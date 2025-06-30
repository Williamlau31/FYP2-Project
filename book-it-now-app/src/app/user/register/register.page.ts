import { Component } from "@angular/core"
import { HttpClientModule } from "@angular/common/http"
import { type Router, RouterModule } from "@angular/router"
import type { ToastController } from "@ionic/angular"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import type { AuthService, RegisterData } from "../../auth.service"

@Component({
  selector: "app-register",
  templateUrl: "./register.page.html",
  styleUrls: ["./register.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, RouterModule, HttpClientModule],
})
export default class RegisterPage {
  registrationData: RegisterData = {
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  }
  validationErrors: any = {}
  loading = false

  constructor(
    private authService: AuthService,
    private router: Router,
    private toastController: ToastController,
  ) {}

  async presentToast(message: string, color = "danger") {
    const toast = await this.toastController.create({
      message: message,
      duration: 3000,
      color: color,
    })
    toast.present()
  }

  register() {
    this.validationErrors = {}
    this.loading = true

    // Basic validation
    if (this.registrationData.password !== this.registrationData.password_confirmation) {
      this.presentToast("Passwords do not match")
      this.loading = false
      return
    }

    if (this.registrationData.password.length < 6) {
      this.presentToast("Password must be at least 6 characters")
      this.loading = false
      return
    }

    this.authService.register(this.registrationData).subscribe({
      next: (response) => {
        console.log("Registration successful", response)
        this.loading = false
        this.presentToast("Registration successful! Please login.", "success")
        this.router.navigate(["/login"])
      },
      error: (error) => {
        console.error("Registration failed", error)
        this.loading = false

        if (error.status === 422 && error.error && error.error.errors) {
          this.validationErrors = error.error.errors
          this.presentToast("Please check the form for errors")
        } else if (error.status === 409) {
          this.presentToast("Email already exists")
        } else {
          this.presentToast("Registration failed. Please try again.")
        }
      },
    })
  }

  clearValidationError(field: string) {
    if (this.validationErrors[field]) {
      delete this.validationErrors[field]
    }
  }
}
