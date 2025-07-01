import { Component, type OnInit, Input } from "@angular/core"
import type { ModalController } from "@ionic/angular"
import type { Staff } from "../shared/models"

@Component({
  selector: "app-staff-modal",
  templateUrl: "./staff-modal.component.html",
})
export class StaffModalComponent implements OnInit {
  @Input() staff?: Staff

  form: any = {
    name: "",
    email: "",
    role: "",
    department: "",
  }

  constructor(private modalController: ModalController) {}

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
