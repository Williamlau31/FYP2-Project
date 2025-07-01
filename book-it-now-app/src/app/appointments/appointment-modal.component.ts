import { Component, type OnInit, Input } from "@angular/core"
import type { ModalController } from "@ionic/angular"
import type { DataService } from "../shared/data.service"
import type { Appointment, Patient, Staff } from "../shared/models"

@Component({
  selector: "app-appointment-modal",
  templateUrl: "./appointment-modal.component.html",
})
export class AppointmentModalComponent implements OnInit {
  @Input() appointment?: Appointment

  form: any = {
    patient_id: null,
    staff_id: null,
    date: "",
    time: "",
    status: "scheduled",
    notes: "",
  }

  patients: Patient[] = []
  staffList: Staff[] = []

  constructor(
    private modalController: ModalController,
    private dataService: DataService,
  ) {}

  ngOnInit() {
    this.dataService.getPatients().subscribe((patients) => {
      this.patients = patients
    })

    this.dataService.getStaff().subscribe((staff) => {
      this.staffList = staff
    })

    if (this.appointment) {
      this.form = { ...this.appointment }
    }
  }

  dismiss() {
    this.modalController.dismiss()
  }

  save() {
    if (this.appointment?.id) {
      this.form.id = this.appointment.id
    }
    this.modalController.dismiss(this.form)
  }
}
