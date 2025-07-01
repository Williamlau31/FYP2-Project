import { Component, type OnInit } from "@angular/core"
import type { ModalController } from "@ionic/angular"
import type { DataService } from "../shared/data.service"
import type { Patient } from "../shared/models"

@Component({
  selector: "app-queue-modal",
  templateUrl: "./queue-modal.component.html",
})
export class QueueModalComponent implements OnInit {
  form: any = {
    patient_id: null,
    status: "waiting",
  }

  patients: Patient[] = []

  constructor(
    private modalController: ModalController,
    private dataService: DataService,
  ) {}

  ngOnInit() {
    this.dataService.getPatients().subscribe((patients) => {
      this.patients = patients
    })
  }

  dismiss() {
    this.modalController.dismiss()
  }

  save() {
    this.modalController.dismiss(this.form)
  }
}
