import { Component } from "@angular/core"
import type { AuthService } from "./auth.service"

@Component({
  selector: "app-root",
  templateUrl: "app.component.html",
  styleUrls: ["app.component.scss"],
})
export class AppComponent {
  constructor(private authService: AuthService) {
    // Auto-login for demo purposes
    if (!this.authService.isLoggedIn()) {
      this.authService.loginAsAdmin().subscribe({
        next: () => console.log("Auto-logged in as admin"),
        error: (error) => console.log("Auto-login failed:", error),
      })
    }
  }
}
