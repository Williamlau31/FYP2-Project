import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { Appointment } from "../models/appointment.model"
import { AppointmentService } from "../services/appointment.service"

@Component({
  selector: "app-appointment-detail",
  templateUrl: "./appointment-detail.page.html",
  styleUrls: ["./appointment-detail.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class AppointmentDetailPage implements OnInit {
  appointment: Appointment | null = null
  loading = false

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private appointmentService: AppointmentService,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.loadAppointment(+id)
    }
  }

  loadAppointment(id: number) {
    this.loading = true
    this.appointmentService.getAppointment(id).subscribe({
      next: (appointment) => {
        this.appointment = appointment
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading appointment:", error)
        this.loading = false
        this.presentToast("Error loading appointment", "danger")
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

  editAppointment() {
    if (this.appointment?.id) {
      this.router.navigate(["/appointment/edit", this.appointment.id])
    }
  }

  async deleteAppointment() {
    if (!this.appointment?.id) return

    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: "Are you sure you want to delete this appointment?",
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
        },
        {
          text: "Delete",
          handler: () => {
            if (this.appointment?.id) {
              this.appointmentService.deleteAppointment(this.appointment.id).subscribe({
                next: () => {
                  this.presentToast("Appointment deleted successfully")
                  this.router.navigate(["/appointment/list"])
                },
                error: (error) => {
                  console.error("Error deleting appointment:", error)
                  this.presentToast("Error deleting appointment", "danger")
                },
              })
            }
          },
        },
      ],
    })
    await alert.present()
  }

  getStatusColor(status: string): string {
    switch (status) {
      case "scheduled":
        return "medium"
      case "confirmed":
        return "primary"
      case "in-progress":
        return "warning"
      case "completed":
        return "success"
      case "cancelled":
        return "danger"
      default:
        return "medium"
    }
  }
}

export default AppointmentDetailPage

