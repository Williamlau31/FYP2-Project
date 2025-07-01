import { Component, type OnInit } from "@angular/core"
import type { ModalController, AlertController, ToastController } from "@ionic/angular"
import type { DataService } from "../shared/data.service"
import type { Patient } from "../shared/models"
import { PatientModalComponent } from "./patient-modal.component"

@Component({
  selector: "app-patients",
  templateUrl: "./patients.page.html",
  styleUrls: ["./patients.page.scss"],
})
export class PatientsPage implements OnInit {
  patients: Patient[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

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
        this.dataService.addPatient(result.data)
        this.showToast("Patient added successfully")
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
        this.dataService.updatePatient(result.data)
        this.showToast("Patient updated successfully")
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
            this.dataService.deletePatient(id)
            this.showToast("Patient deleted successfully")
          },
        },
      ],
    })
    await alert.present()
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

export default PatientsPage
