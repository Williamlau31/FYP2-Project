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
  IonSelect,
  IonSelectOption,
  ModalController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { close } from "ionicons/icons"
import { Staff } from "../shared/models"

@Component({
  selector: "app-staff-modal",
  templateUrl: "./staff-modal.component.html",
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
    IonSelect,
    IonSelectOption,
  ],
})
export class StaffModalComponent implements OnInit {
  @Input() staff?: Staff

  form: any = {
    name: "",
    email: "",
    role: "",
    department: "",
  }

  constructor(private modalController: ModalController) {
    addIcons({ close })
  }

  ngOnInit() {
    if (this.staff) {
      this.form = { ...this.staff }
    }
  }

  dismiss() {
    this.modalController.dismiss()
  }

  save() {
    if (this.staff?.id) {
      this.form.id = this.staff.id
    }
    this.modalController.dismiss(this.form)
  }
}
