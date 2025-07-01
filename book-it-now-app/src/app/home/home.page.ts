import { Component, type OnInit } from "@angular/core"
import type { Router } from "@angular/router"
import type { AuthService } from "../shared/auth.service"

@Component({
  selector: "app-home",
  templateUrl: "./home.page.html",
  styleUrls: ["./home.page.scss"],
})
export class HomePage implements OnInit {
  currentUser: any = null

  constructor(
    private authService: AuthService,
    private router: Router,
  ) {}

  ngOnInit() {
    this.authService.currentUser$.subscribe((user) => {
      this.currentUser = user
    })
  }

  get isAdmin() {
    return this.authService.isAdmin()
  }

  navigate(path: string) {
    this.router.navigate([path])
  }

  logout() {
    this.authService.logout()
    this.router.navigate(["/login"])
  }
}

export default HomePage
