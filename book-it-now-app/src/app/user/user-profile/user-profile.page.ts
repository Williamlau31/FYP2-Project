import { Component, type OnInit } from "@angular/core"
import { type Router, RouterModule } from "@angular/router"
import { CommonModule } from "@angular/common"
import { IonicModule, type ToastController } from "@ionic/angular"
import type { AuthService, User } from "../../auth.service"

@Component({
  selector: "app-user-profile",
  templateUrl: "./user-profile.page.html",
  styleUrls: ["./user-profile.page.scss"],
  standalone: true,
  imports: [CommonModule, IonicModule, RouterModule],
})
export class UserProfilePage implements OnInit {
  user: User | null = null
  loading = false

  constructor(
    private router: Router,
    private authService: AuthService,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    this.loadUserProfile()
  }

  loadUserProfile() {
    this.loading = true

    // First try to get user from current session
    this.user = this.authService.getCurrentUser()

    if (this.user) {
      this.loading = false
      return
    }

    // If no user in session, fetch from API
    this.authService.getProfile().subscribe({
      next: (user) => {
        this.user = user
        this.loading = false
      },
      error: (error) => {
        console.error("Failed to load profile:", error)
        this.loading = false
        this.presentToast("Failed to load profile", "danger")

        // If unauthorized, redirect to login
        if (error.status === 401) {
          this.router.navigate(["/login"])
        }
      },
    })
  }

  async presentToast(message: string, color = "success") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }

  goToEditProfile() {
    this.router.navigate(["/user/edit-profile"])
  }

  logout() {
    this.loading = true

    this.authService.logout().subscribe({
      next: () => {
        this.loading = false
        this.presentToast("Logged out successfully", "success")
        this.router.navigate(["/login"])
      },
      error: (error) => {
        console.error("Logout error:", error)
        this.loading = false

        // Even if logout fails on server, clear local storage and redirect
        localStorage.removeItem("auth_token")
        localStorage.removeItem("current_user")
        this.presentToast("Logged out", "success")
        this.router.navigate(["/login"])
      },
    })
  }

  getUserInitials(): string {
    if (!this.user?.name) return "U"
    return this.user.name
      .split(" ")
      .map((n) => n[0])
      .join("")
      .toUpperCase()
  }

  getDefaultAvatar(): string {
    return "/assets/images/default-avatar.png"
  }
}

export default UserProfilePage
