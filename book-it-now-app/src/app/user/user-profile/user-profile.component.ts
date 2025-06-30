import { Component, type OnInit, Input, Output, EventEmitter } from "@angular/core"
import type { User } from "../../auth.service"

@Component({
  selector: "app-user-profile-component",
  templateUrl: "./user-profile.component.html",
  styleUrls: ["./user-profile.component.scss"],
})
export class UserProfileComponent implements OnInit {
  @Input() user: User | null = null
  @Output() editProfile = new EventEmitter<void>()
  @Output() logout = new EventEmitter<void>()

  constructor() {}

  ngOnInit() {}

  getUserInitials(): string {
    if (!this.user?.name) return "U"
    return this.user.name
      .split(" ")
      .map((n) => n[0])
      .join("")
      .toUpperCase()
  }

  onAvatarError(event: any) {
    event.target.style.display = "none"
  }

  onEditProfile() {
    this.editProfile.emit()
  }

  onLogout() {
    this.logout.emit()
  }
}
