import { Component, type OnInit } from "@angular/core"
import type { ModalController, AlertController, ToastController } from "@ionic/angular"
import type { DataService } from "../shared/data.service"
import type { Staff } from "../shared/models"
import { StaffModalComponent } from "./staff-modal.component"

@Component({
  selector: "app-staff",
  templateUrl: "./staff.page.html",
  styleUrls: ["./staff.page.scss"],
})
export class StaffPage implements OnInit {
  staff: Staff[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    this.dataService.getStaff().subscribe((staff) => {
      this.staff = staff
    })
  }

  async openAddModal() {
    const modal = await this.modalController.create({
      component: StaffModalComponent,
    })

    modal.onDidDismiss().then((result) => {
      if (result.data) {
        this.dataService.addStaff(result.data)
        this.showToast("Staff member added successfully")
      }
    })

    return await modal.present()
  }

  async editStaff(staff: Staff) {
    const modal = await this.modalController.create({
      component: StaffModalComponent,
      componentProps: { staff },
    })

    modal.onDidDismiss().then((result) => {
      if (result.data) {
        this.dataService.updateStaff(result.data)
        this.showToast("Staff member updated successfully")
      }
    })

    return await modal.present()
  }

  async deleteStaff(id: number) {
    const alert = await this.alertController.create({
      header: "Confirm Delete",
      message: "Are you sure you want to delete this staff member?",
      buttons: [
        { text: "Cancel", role: "cancel" },
        {
          text: "Delete",
          handler: () => {
            this.dataService.deleteStaff(id)
            this.showToast("Staff member deleted successfully")
          },
        },
      ],
    })
    await alert.present()
  }

  async showToast(message: string) {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color: "success",
    })
    toast.present()
  }
}

export default StaffPage
