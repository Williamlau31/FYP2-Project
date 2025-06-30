import { Component } from "@angular/core"
import { HttpClient, HttpClientModule } from "@angular/common/http"
import { Router, RouterModule } from "@angular/router"
import { ToastController } from "@ionic/angular"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"

const API_URL = "http://localhost:8000/api"

@Component({
  selector: "app-register",
  templateUrl: "./register.page.html",
  styleUrls: ["./register.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, RouterModule, HttpClientModule],
})
export default class RegisterPage {
  registrationData = {
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  }
  validationErrors: any = {}

  constructor(
    private http: HttpClient,
    private router: Router,
    private toastController: ToastController,
  ) {}

  async presentToast(message: string, color = "danger") {
    const toast = await this.toastController.create({
      message: message,
      duration: 2000,
      color: color,
    })
    toast.present()
  }

  register() {
    this.validationErrors = {}

    this.http.post<any>(`${API_URL}/register`, this.registrationData).subscribe(
      (response) => {
        console.log("Registration successful", response)
        this.presentToast("Registration successful", "success")
        this.router.navigate(["/login"])
      },
      (error) => {
        console.error("Registration failed", error)
        if (error.status === 422 && error.error && error.error.errors) {
          this.validationErrors = error.error.errors
          this.presentToast("Validation errors occurred")
        } else {
          this.presentToast("Registration failed. Please try again.")
        }
      },
    )
  }
}

