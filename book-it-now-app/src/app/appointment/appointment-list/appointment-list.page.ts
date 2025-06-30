import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { Appointment } from "../models/appointment.model"
import { AppointmentService } from "../services/appointment.service"

@Component({
  selector: "app-appointment-list",
  templateUrl: "./appointment-list.page.html",
  styleUrls: ["./appointment-list.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class AppointmentListPage implements OnInit {
  appointments: Appointment[] = []
  loading = false
  selectedSegment = "all"

  constructor(
    private appointmentService: AppointmentService,
    private router: Router,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    this.loadAppointments()
  }

  ionViewWillEnter() {
    this.loadAppointments()
  }

  loadAppointments() {
    this.loading = true
    this.appointmentService.getAppointments().subscribe({
      next: (appointments) => {
        this.appointments = appointments
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading appointments:", error)
        this.loading = false
        this.presentToast("Error loading appointments", "danger")
      },
    })
  }

  get filteredAppointments() {
    if (this.selectedSegment === "all") {
      return this.appointments
    }
    return this.appointments.filter((apt) => apt.status === this.selectedSegment)
  }

  async presentToast(message: string, color = "success") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }

  viewAppointment(id: number) {
    this.router.navigate(["/appointment/detail", id])
  }

  editAppointment(id: number) {
    this.router.navigate(["/appointment/edit", id])
  }

  async deleteAppointment(appointment: Appointment) {
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
            if (appointment.id) {
              this.appointmentService.deleteAppointment(appointment.id).subscribe({
                next: () => {
                  this.presentToast("Appointment deleted successfully")
                  this.loadAppointments()
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

  createAppointment() {
    this.router.navigate(["/appointment/create"])
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

export default AppointmentListPage

