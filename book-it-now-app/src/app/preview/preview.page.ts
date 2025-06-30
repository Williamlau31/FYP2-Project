import { Component } from "@angular/core"
import { Router } from "@angular/router"
import { CommonModule } from "@angular/common"
import { IonicModule } from "@ionic/angular"
import { AuthService } from "../auth.service"

@Component({
  selector: "app-preview",
  templateUrl: "./preview.page.html",
  styleUrls: ["./preview.page.scss"],
  standalone: true,
  imports: [CommonModule, IonicModule],
})
export class PreviewPage {
  constructor(
    private router: Router,
    private authService: AuthService,
  ) {}

  loginAsAdmin() {
    this.authService.loginAsAdmin()
    this.router.navigate(["/home"])
  }

  loginAsUser() {
    this.authService.loginAsUser()
    this.router.navigate(["/home"])
  }

  goToLogin() {
    this.router.navigate(["/login"])
  }

  goToModule(path: string) {
    // Auto-login as admin for module testing
    this.authService.loginAsAdmin()
    this.router.navigate([path])
  }
}

export default PreviewPage


