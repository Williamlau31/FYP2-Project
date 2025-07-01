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
  IonItemOptions,
  IonItemOption,
  ModalController,
  AlertController,
  ToastController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { add, create, trash } from "ionicons/icons"
import { DataService } from "../shared/data.service"
import { Patient } from "../shared/models"
import { PatientModalComponent } from "./patient-modal.component"

@Component({
  selector: "app-patients",
  templateUrl: "./patients.page.html",
  styleUrls: ["./patients.page.scss"],
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
    IonItemOptions,
    IonItemOption,
  ],
})
export class PatientsPage implements OnInit {
  patients: Patient[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {
    addIcons({ add, create, trash })
  }

  ngOnInit() {
    this.dataService.getPatients().subscribe((patients) => {
      this.patients = patients
    })
  }

  async openAddModal() {
    const modal = await this.modalController.create({
      component: PatientModalComponent,
    })

    modal.onDidDismiss().then((result) => {
      if (result.data) {
        this.dataService.addPatient(result.data).subscribe({
          next: () => {
            this.showToast("Patient added successfully")
          },
          error: (error) => {
            console.error("Add patient error:", error)
            this.showToast("Failed to add patient", "danger")
          },
        })
      }
    })

    return await modal.present()
  }

  async editPatient(patient: Patient) {
    const modal = await this.modalController.create({
      component: PatientModalComponent,
      componentProps: { patient },
    })

    modal.onDidDismiss().then((result) => {
      if (result.data) {
        this.dataService.updatePatient(result.data).subscribe({
          next: () => {
            this.showToast("Patient updated successfully")
          },
          error: (error) => {
            console.error("Update patient error:", error)
            this.showToast("Failed to update patient", "danger")
          },
        })
      }
    })

    return await modal.present()
  }

  async deletePatient(id: number) {
    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: "Are you sure you want to delete this patient?",
      buttons: [
        { text: "Cancel", role: "cancel" },
        {
          text: "Delete",
          handler: () => {
            this.dataService.deletePatient(id).subscribe({
              next: () => {
                this.showToast("Patient deleted successfully")
              },
              error: (error) => {
                console.error("Delete patient error:", error)
                this.showToast("Failed to delete patient", "danger")
              },
            })
          },
        },
      ],
    })
    await alert.present()
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

export default PatientsPage
