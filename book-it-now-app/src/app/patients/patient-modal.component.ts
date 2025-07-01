import { Component, type OnInit, Input } from "@angular/core"
import type { ModalController } from "@ionic/angular"
import type { Patient } from "../shared/models"

@Component({
  selector: "app-patient-modal",
  templateUrl: "./patient-modal.component.html",
})
export class PatientModalComponent implements OnInit {
  @Input() patient?: Patient

  form: any = {
    name: "",
    email: "",
    phone: "",
    address: "",
  }

  constructor(private modalController: ModalController) {}

  ngOnInit() {
    if (this.patient) {
      this.form = { ...this.patient }
    }
  }

  dismiss() {
    this.modalController.dismiss()
  }

  save() {
    if (this.patient?.id) {
      this.form.id = this.patient.id
    }
    this.modalController.dismiss(this.form)
  }
}
