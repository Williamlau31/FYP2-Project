import { Component, type OnInit } from "@angular/core"
import type { AuthService, User } from "../auth.service"

@Component({
  selector: "app-home",
  templateUrl: "./home.page.html",
  styleUrls: ["./home.page.scss"],
})
export class HomePage implements OnInit {
  currentUser: User | null = null

  constructor(private authService: AuthService) {}

  ngOnInit() {
    this.authService.currentUser$.subscribe((user) => {
      this.currentUser = user
    })
  }

  get isAdmin() {
    return this.authService.isAdmin()
  }

  get isUser() {
    return this.authService.isUser()
  }
}

export default HomePage
