import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { Staff } from "../models/staff.model"
import { StaffService } from "../services/staff.service"

@Component({
  selector: "app-staff-list",
  templateUrl: "./staff-list.page.html",
  styleUrls: ["./staff-list.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class StaffListPage implements OnInit {
  staff: Staff[] = []
  loading = false
  selectedSegment = "all"

  constructor(
    private staffService: StaffService,
    private router: Router,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    this.loadStaff()
  }

  ionViewWillEnter() {
    this.loadStaff()
  }

  loadStaff() {
    this.loading = true
    this.staffService.getStaff().subscribe({
      next: (staff) => {
        this.staff = staff
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading staff:", error)
        this.loading = false
        this.presentToast("Error loading staff", "danger")
      },
    })
  }

  get filteredStaff() {
    if (this.selectedSegment === "all") {
      return this.staff
    }
    return this.staff.filter((s) => s.role === this.selectedSegment)
  }

  async presentToast(message: string, color = "success") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }

  viewStaff(id: number) {
    this.router.navigate(["/staff/detail", id])
  }

  editStaff(id: number) {
    this.router.navigate(["/staff/edit", id])
  }

  async deleteStaff(staff: Staff) {
    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: `Are you sure you want to delete ${staff.firstName} ${staff.lastName}?`,
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
        },
        {
          text: "Delete",
          handler: () => {
            if (staff.id) {
              this.staffService.deleteStaff(staff.id).subscribe({
                next: () => {
                  this.presentToast("Staff member deleted successfully")
                  this.loadStaff()
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

  createStaff() {
    this.router.navigate(["/staff/create"])
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

export default StaffListPage

