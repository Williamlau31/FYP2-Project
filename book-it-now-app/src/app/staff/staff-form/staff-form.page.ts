import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { ToastController } from "@ionic/angular"
import type { Staff } from "../models/staff.model"
import { StaffService } from "../services/staff.service"

@Component({
  selector: "app-staff-form",
  templateUrl: "./staff-form.page.html",
  styleUrls: ["./staff-form.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class StaffFormPage implements OnInit {
  staff: Staff = {
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    role: "nurse",
    department: "",
    specialization: "",
    licenseNumber: "",
    hireDate: "",
    status: "active",
    workingHours: "",
  }

  isEdit = false
  loading = false

  roles = ["doctor", "nurse", "receptionist", "admin", "technician"]
  departments = ["Cardiology", "Orthopedics", "General", "Emergency", "Pediatrics", "Surgery", "Radiology"]
  statuses = ["active", "inactive", "on-leave"]

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private staffService: StaffService,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.isEdit = true
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

  onSubmit() {
    this.loading = true

    if (this.isEdit && this.staff.id) {
      this.staffService.updateStaff(this.staff.id, this.staff).subscribe({
        next: () => {
          this.loading = false
          this.presentToast("Staff member updated successfully")
          this.router.navigate(["/staff/detail", this.staff.id])
        },
        error: (error) => {
          console.error("Error updating staff:", error)
          this.loading = false
          this.presentToast("Error updating staff member", "danger")
        },
      })
    } else {
      this.staffService.createStaff(this.staff).subscribe({
        next: (staff) => {
          this.loading = false
          this.presentToast("Staff member created successfully")
          this.router.navigate(["/staff/detail", staff.id])
        },
        error: (error) => {
          console.error("Error creating staff:", error)
          this.loading = false
          this.presentToast("Error creating staff member", "danger")
        },
      })
    }
  }
}

export default StaffFormPage

