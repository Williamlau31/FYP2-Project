import { Component, type OnInit } from "@angular/core"
import { Router } from "@angular/router"
import { CommonModule } from "@angular/common"
import { IonicModule } from "@ionic/angular"
import { RouterModule } from "@angular/router"
import { AuthService, User } from "../auth.service"

@Component({
  selector: "app-home",
  templateUrl: "./home.page.html",
  styleUrls: ["./home.page.scss"],
  standalone: true,
  imports: [CommonModule, IonicModule, RouterModule],
})
export class HomePage implements OnInit {
  currentUser: User | null = null

  constructor(
    private router: Router,
    private authService: AuthService,
  ) {}

  ngOnInit() {
    this.authService.currentUser$.subscribe((user) => {
      this.currentUser = user
      if (!user) {
        this.router.navigate(["/login"])
      }
    })
  }

  get isAdmin(): boolean {
    return this.authService.isAdmin()
  }

  get isUser(): boolean {
    return this.authService.isUser()
  }

  logout() {
    this.authService.logout()
    this.router.navigate(["/login"])
  }

  // Navigation methods
  navigateToPatients() {
    console.log("Navigating to patients...")
    this.router.navigate(["/patient/list"])
  }

  navigateToStaff() {
    console.log("Navigating to staff...")
    this.router.navigate(["/staff/list"])
  }

  navigateToAppointments() {
    console.log("Navigating to appointments...")
    this.router.navigate(["/appointment/list"])
  }

  navigateToQueue() {
    console.log("Navigating to queue...")
    this.router.navigate(["/queue/list"])
  }

  navigateToReports() {
    console.log("Navigating to reports...")
    this.router.navigate(["/reporting/dashboard"])
  }

  navigateToCreateAppointment() {
    console.log("Navigating to create appointment...")
    this.router.navigate(["/appointment/create"])
  }

  navigateToProfile() {
    console.log("Navigating to profile...")
    this.router.navigate(["/user-profile"])
  }
}

export default HomePage


