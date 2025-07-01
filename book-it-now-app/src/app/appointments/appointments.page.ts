import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonButtons,
  IonBackButton,
  IonButton,
  IonIcon,
  IonList,
  IonItemSliding,
  IonItem,
  IonLabel,
  IonChip,
  IonItemOptions,
  IonItemOption,
  ModalController,
  AlertController,
  ToastController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { add, create, trash, calendar, calendarOutline, checkmarkCircle, closeCircle, today, time, person, medical, documentText } from "ionicons/icons"
import { DataService } from "../shared/data.service"
import { Appointment } from "../shared/models"
import { AppointmentModalComponent } from "./appointment-modal.component"

@Component({
  selector: "app-appointments",
  templateUrl: "./appointments.page.html",
  styleUrls: ["./appointments.page.scss"],
  standalone: true,
  imports: [
    CommonModule,
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonBackButton,
    IonButton,
    IonIcon,
    IonList,
    IonItemSliding,
    IonItem,
    IonLabel,
    IonChip,
    IonItemOptions,
    IonItemOption,
  ],
})
export class AppointmentsPage implements OnInit {
  appointments: Appointment[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {
    addIcons({calendar,add,calendarOutline,checkmarkCircle,closeCircle,today,time,person,medical,documentText,create,trash});
  }

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
        this.dataService.addAppointment(result.data).subscribe({
          next: () => {
            this.showToast("Appointment added successfully")
          },
          error: (error) => {
            console.error("Add appointment error:", error)
            this.showToast("Failed to add appointment", "danger")
          },
        })
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
        this.dataService.updateAppointment(result.data).subscribe({
          next: () => {
            this.showToast("Appointment updated successfully")
          },
          error: (error) => {
            console.error("Update appointment error:", error)
            this.showToast("Failed to update appointment", "danger")
          },
        })
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
            this.dataService.deleteAppointment(id).subscribe({
              next: () => {
                this.showToast("Appointment deleted successfully")
              },
              error: (error) => {
                console.error("Delete appointment error:", error)
                this.showToast("Failed to delete appointment", "danger")
              },
            })
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

  async showToast(message: string, color = "success") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }
}

export default AppointmentsPage
