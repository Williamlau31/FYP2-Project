import { Component, type OnInit } from "@angular/core"
import { Router, RouterModule } from "@angular/router"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { AuthService } from "../../auth.service"
import { ToastController } from "@ionic/angular"

@Component({
  selector: "app-login",
  templateUrl: "./login.page.html",
  styleUrls: ["./login.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, RouterModule],
})
export class LoginPage implements OnInit {
  credentials = {
    email: "",
    password: "",
  }
  loading = false

  constructor(
    private router: Router,
    private authService: AuthService,
    private toastController: ToastController,
  ) {}

  ngOnInit() {}

  async presentToast(message: string, color = "danger") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }

  onSubmit() {
    this.loading = true

    this.authService.login(this.credentials.email, this.credentials.password).subscribe({
      next: (user) => {
        this.loading = false
        this.presentToast(`Welcome ${user.name}!`, "success")
        this.router.navigate(["/home"])
      },
      error: (error) => {
        this.loading = false
        this.presentToast(error.message || "Login failed")
      },
    })
  }

  // Quick login methods for testing
  loginAsAdmin() {
    this.authService.loginAsAdmin()
    this.presentToast("Logged in as Admin!", "success")
    this.router.navigate(["/home"])
  }

  loginAsUser() {
    this.authService.loginAsUser()
    this.presentToast("Logged in as User!", "success")
    this.router.navigate(["/home"])
  }
}

export default LoginPage


