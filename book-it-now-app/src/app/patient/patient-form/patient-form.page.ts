import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { ToastController } from "@ionic/angular"
import type { Patient } from "../models/patient.model"
import { PatientService } from "../services/patient.service"

@Component({
  selector: "app-patient-form",
  templateUrl: "./patient-form.page.html",
  styleUrls: ["./patient-form.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class PatientFormPage implements OnInit {
  patient: Patient = {
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    dateOfBirth: "",
    address: "",
    emergencyContact: "",
    emergencyPhone: "",
    medicalHistory: "",
    allergies: "",
  }

  isEdit = false
  loading = false

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private patientService: PatientService,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.isEdit = true
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

  onSubmit() {
    this.loading = true

    if (this.isEdit && this.patient.id) {
      this.patientService.updatePatient(this.patient.id, this.patient).subscribe({
        next: () => {
          this.loading = false
          this.presentToast("Patient updated successfully")
          this.router.navigate(["/patient/detail", this.patient.id])
        },
        error: (error) => {
          console.error("Error updating patient:", error)
          this.loading = false
          this.presentToast("Error updating patient", "danger")
        },
      })
    } else {
      this.patientService.createPatient(this.patient).subscribe({
        next: (patient) => {
          this.loading = false
          this.presentToast("Patient created successfully")
          this.router.navigate(["/patient/detail", patient.id])
        },
        error: (error) => {
          console.error("Error creating patient:", error)
          this.loading = false
          this.presentToast("Error creating patient", "danger")
        },
      })
    }
  }
}

export default PatientFormPage

