import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { ToastController } from "@ionic/angular"
import type { Appointment } from "../models/appointment.model"
import { AppointmentService } from "../services/appointment.service"

@Component({
  selector: "app-appointment-form",
  templateUrl: "./appointment-form.page.html",
  styleUrls: ["./appointment-form.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class AppointmentFormPage implements OnInit {
  appointment: Appointment = {
    patientId: 1,
    staffId: 1,
    appointmentDate: "",
    appointmentTime: "",
    duration: 30,
    type: "",
    status: "scheduled",
    notes: "",
  }

  isEdit = false
  loading = false

  appointmentTypes = ["Consultation", "Follow-up", "Check-up", "Surgery", "Emergency", "Vaccination", "Lab Test"]

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private appointmentService: AppointmentService,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.isEdit = true
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

  onSubmit() {
    this.loading = true

    if (this.isEdit && this.appointment.id) {
      this.appointmentService.updateAppointment(this.appointment.id, this.appointment).subscribe({
        next: () => {
          this.loading = false
          this.presentToast("Appointment updated successfully")
          this.router.navigate(["/appointment/detail", this.appointment.id])
        },
        error: (error) => {
          console.error("Error updating appointment:", error)
          this.loading = false
          this.presentToast("Error updating appointment", "danger")
        },
      })
    } else {
      this.appointmentService.createAppointment(this.appointment).subscribe({
        next: (appointment) => {
          this.loading = false
          this.presentToast("Appointment created successfully")
          this.router.navigate(["/appointment/detail", appointment.id])
        },
        error: (error) => {
          console.error("Error creating appointment:", error)
          this.loading = false
          this.presentToast("Error creating appointment", "danger")
        },
      })
    }
  }
}

export default AppointmentFormPage

