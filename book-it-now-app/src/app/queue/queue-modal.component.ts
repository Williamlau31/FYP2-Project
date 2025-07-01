import { Component, type OnInit } from "@angular/core"
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
  ModalController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { close } from "ionicons/icons"
import { DataService } from "../shared/data.service"
import { Patient } from "../shared/models"

@Component({
  selector: "app-queue-modal",
  templateUrl: "./queue-modal.component.html",
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
  ],
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
  ) {
    addIcons({ close })
  }

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
