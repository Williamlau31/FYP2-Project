import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { Patient } from "../models/patient.model"
import { PatientService } from "../services/patient.service"

@Component({
  selector: "app-patient-list",
  templateUrl: "./patient-list.page.html",
  styleUrls: ["./patient-list.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class PatientListPage implements OnInit {
  patients: Patient[] = []
  loading = false

  constructor(
    private patientService: PatientService,
    private router: Router,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    this.loadPatients()
  }

  ionViewWillEnter() {
    this.loadPatients()
  }

  loadPatients() {
    this.loading = true
    this.patientService.getPatients().subscribe({
      next: (patients) => {
        this.patients = patients
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading patients:", error)
        this.loading = false
        this.presentToast("Error loading patients", "danger")
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

  viewPatient(id: number) {
    this.router.navigate(["/patient/detail", id])
  }

  editPatient(id: number) {
    this.router.navigate(["/patient/edit", id])
  }

  async deletePatient(patient: Patient) {
    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: `Are you sure you want to delete ${patient.firstName} ${patient.lastName}?`,
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
        },
        {
          text: "Delete",
          handler: () => {
            if (patient.id) {
              this.patientService.deletePatient(patient.id).subscribe({
                next: () => {
                  this.presentToast("Patient deleted successfully")
                  this.loadPatients()
                },
                error: (error) => {
                  console.error("Error deleting patient:", error)
                  this.presentToast("Error deleting patient", "danger")
                },
              })
            }
          },
        },
      ],
    })
    await alert.present()
  }

  createPatient() {
    this.router.navigate(["/patient/create"])
  }
}

export default PatientListPage


