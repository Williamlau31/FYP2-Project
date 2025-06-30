import { Component, type OnInit } from "@angular/core"
import { Router, RouterModule } from "@angular/router"
import { CommonModule } from "@angular/common"
import { IonicModule } from "@ionic/angular"

@Component({
  selector: "app-user-profile",
  templateUrl: "./user-profile.page.html",
  styleUrls: ["./user-profile.page.scss"],
  standalone: true,
  imports: [CommonModule, IonicModule, RouterModule],
})
export class UserProfilePage implements OnInit {
  user: any

  constructor(private router: Router) {}

  ngOnInit() {
    this.user = {
      name: "John Doe",
      email: "john.doe@example.com",
    }
  }

  goToEditProfile() {
    this.router.navigate(["/user/edit-profile"])
  }

  logout() {
    // Clear authentication data (example using localStorage)
    localStorage.removeItem("access_token")
    // Navigate to the login page
    this.router.navigate(["/login"])
  }
}

export default UserProfilePage

