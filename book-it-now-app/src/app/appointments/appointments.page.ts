import { Component, type OnInit } from "@angular/core"
import type { ModalController, AlertController, ToastController } from "@ionic/angular"
import type { DataService } from "../shared/data.service"
import type { Appointment } from "../shared/models"
import { AppointmentModalComponent } from "./appointment-modal.component"

@Component({
  selector: "app-appointments",
  templateUrl: "./appointments.page.html",
  styleUrls: ["./appointments.page.scss"],
})
export class AppointmentsPage implements OnInit {
  appointments: Appointment[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    this.dataService.getAppointments().subscribe((appointments) => {
      this.appointments = appointments
    })
  }

  async openAddModal() {
    const modal = await this.modalController.create({
      component: AppointmentModalComponent,
    })

    modal.onDidDismiss().then((result) => {
      if (result.data) {
        this.dataService.addAppointment(result.data)
        this.showToast("Appointment added successfully")
      }
    })

    return await modal.present()
  }

  async editAppointment(appointment: Appointment) {
    const modal = await this.modalController.create({
      component: AppointmentModalComponent,
      componentProps: { appointment },
    })

    modal.onDidDismiss().then((result) => {
      if (result.data) {
        this.dataService.updateAppointment(result.data)
        this.showToast("Appointment updated successfully")
      }
    })

    return await modal.present()
  }

  async deleteAppointment(id: number) {
    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: "Are you sure you want to delete this appointment?",
      buttons: [
        { text: "Cancel", role: "cancel" },
        {
          text: "Delete",
          handler: () => {
            this.dataService.deleteAppointment(id)
            this.showToast("Appointment deleted successfully")
          },
        },
      ],
    })
    await alert.present()
  }

  getStatusColor(status: string): string {
    switch (status) {
      case "scheduled":
        return "primary"
      case "completed":
        return "success"
      case "cancelled":
        return "danger"
      default:
        return "medium"
    }
  }

  async showToast(message: string) {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color: "success",
    })
    toast.present()
  }
}

export default AppointmentsPage
