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
  IonInput,
  IonTextarea,
  ModalController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { close } from "ionicons/icons"
import { Patient } from "../shared/models"

@Component({
  selector: "app-patient-modal",
  templateUrl: "./patient-modal.component.html",
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
    IonInput,
    IonTextarea,
  ],
})
export class PatientModalComponent implements OnInit {
  @Input() patient?: Patient

  form: any = {
    name: "",
    email: "",
    phone: "",
    address: "",
  }

  constructor(private modalController: ModalController) {
    addIcons({ close })
  }

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
