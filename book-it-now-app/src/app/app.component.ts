import { Component, type OnInit } from "@angular/core"
import { AuthService } from "./auth.service"

@Component({
  selector: "app-root",
  templateUrl: "app.component.html",
  styleUrls: ["app.component.scss"],
  standalone: false,
})
export class AppComponent implements OnInit {
  constructor(private authService: AuthService) {}

  ngOnInit() {
    // Auto-login as admin for testing purposes
    console.log("🚀 App starting - Auto-login as admin for testing")
    if (!this.authService.isLoggedIn()) {
      this.authService.loginAsAdmin()
      console.log("✅ Auto-logged in as admin")
    }
  }
}


