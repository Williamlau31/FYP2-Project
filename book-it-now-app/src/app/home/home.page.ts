import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { Router } from "@angular/router"
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonButtons,
  IonButton,
  IonIcon,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardSubtitle,
  IonCardContent,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { logOutOutline, calendar, people, medical, time } from "ionicons/icons"
import { AuthService } from "../shared/auth.service"
import { DataService } from "../shared/data.service"

@Component({
  selector: "app-home",
  templateUrl: "./home.page.html",
  styleUrls: ["./home.page.scss"],
  standalone: true,
  imports: [
    CommonModule,
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonButton,
    IonIcon,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardSubtitle,
    IonCardContent,
  ],
})
export class HomePage implements OnInit {
  currentUser: any = null

  constructor(
    private authService: AuthService,
    private router: Router,
    private dataService: DataService,
  ) {
    addIcons({ logOutOutline, calendar, people, medical, time })
  }

  ngOnInit() {
    this.authService.currentUser$.subscribe((user) => {
      this.currentUser = user
      if (user) {
        // Refresh all data when user logs in
        this.dataService.refreshAll()
      }
    })
  }

  get isAdmin() {
    return this.authService.isAdmin()
  }

  navigate(path: string) {
    this.router.navigate([path])
  }

  logout() {
    this.authService.logout().subscribe({
      next: () => {
        this.router.navigate(["/login"])
      },
      error: (error) => {
        console.error("Logout error:", error)
        // Navigate anyway since we cleared local storage
        this.router.navigate(["/login"])
      },
    })
  }
}

export default HomePage
