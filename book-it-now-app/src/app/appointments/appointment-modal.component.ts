import { Component, type OnInit, Input } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonButtons,
  IonButton,
  IonIcon,
  IonItem,
  IonLabel,
  IonSelect,
  IonSelectOption,
  IonDatetime,
  IonTextarea,
  ModalController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { close } from "ionicons/icons"
import { DataService } from "../shared/data.service"
import { Appointment, Patient, Staff } from "../shared/models"

@Component({
  selector: "app-appointment-modal",
  templateUrl: "./appointment-modal.component.html",
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonButton,
    IonIcon,
    IonItem,
    IonLabel,
    IonSelect,
    IonSelectOption,
    IonDatetime,
    IonTextarea,
  ],
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
  ) {
    addIcons({ close })
  }

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
