import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { Patient } from "../models/patient.model"
import { PatientService } from "../services/patient.service"

@Component({
  selector: "app-patient-detail",
  templateUrl: "./patient-detail.page.html",
  styleUrls: ["./patient-detail.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class PatientDetailPage implements OnInit {
  patient: Patient | null = null
  loading = false

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private patientService: PatientService,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.loadPatient(+id)
    }
  }

  loadPatient(id: number) {
    this.loading = true
    this.patientService.getPatient(id).subscribe({
      next: (patient) => {
        this.patient = patient
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading patient:", error)
        this.loading = false
        this.presentToast("Error loading patient", "danger")
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

  editPatient() {
    if (this.patient?.id) {
      this.router.navigate(["/patient/edit", this.patient.id])
    }
  }

  async deletePatient() {
    if (!this.patient?.id) return

    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: `Are you sure you want to delete ${this.patient.firstName} ${this.patient.lastName}?`,
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
        },
        {
          text: "Delete",
          handler: () => {
            if (this.patient?.id) {
              this.patientService.deletePatient(this.patient.id).subscribe({
                next: () => {
                  this.presentToast("Patient deleted successfully")
                  this.router.navigate(["/patient/list"])
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
}

export default PatientDetailPage

