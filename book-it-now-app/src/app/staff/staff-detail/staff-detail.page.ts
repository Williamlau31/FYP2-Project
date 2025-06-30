import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { Staff } from "../models/staff.model"
import { StaffService } from "../services/staff.service"

@Component({
  selector: "app-staff-detail",
  templateUrl: "./staff-detail.page.html",
  styleUrls: ["./staff-detail.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class StaffDetailPage implements OnInit {
  staff: Staff | null = null
  loading = false

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private staffService: StaffService,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.loadStaff(+id)
    }
  }

  loadStaff(id: number) {
    this.loading = true
    this.staffService.getStaffMember(id).subscribe({
      next: (staff) => {
        this.staff = staff
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading staff:", error)
        this.loading = false
        this.presentToast("Error loading staff member", "danger")
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

  editStaff() {
    if (this.staff?.id) {
      this.router.navigate(["/staff/edit", this.staff.id])
    }
  }

  async deleteStaff() {
    if (!this.staff?.id) return

    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: `Are you sure you want to delete ${this.staff.firstName} ${this.staff.lastName}?`,
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
        },
        {
          text: "Delete",
          handler: () => {
            if (this.staff?.id) {
              this.staffService.deleteStaff(this.staff.id).subscribe({
                next: () => {
                  this.presentToast("Staff member deleted successfully")
                  this.router.navigate(["/staff/list"])
                },
                error: (error) => {
                  console.error("Error deleting staff:", error)
                  this.presentToast("Error deleting staff member", "danger")
                },
              })
            }
          },
        },
      ],
    })
    await alert.present()
  }

  getRoleColor(role: string): string {
    switch (role) {
      case "doctor":
        return "primary"
      case "nurse":
        return "secondary"
      case "receptionist":
        return "tertiary"
      case "admin":
        return "warning"
      case "technician":
        return "success"
      default:
        return "medium"
    }
  }

  getStatusColor(status: string): string {
    switch (status) {
      case "active":
        return "success"
      case "inactive":
        return "danger"
      case "on-leave":
        return "warning"
      default:
        return "medium"
    }
  }
}

export default StaffDetailPage


