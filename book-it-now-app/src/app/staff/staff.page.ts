import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonButtons,
  IonBackButton,
  IonButton,
  IonIcon,
  IonList,
  IonItemSliding,
  IonItem,
  IonLabel,
  IonItemOptions,
  IonItemOption,
  ModalController,
  AlertController,
  ToastController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { add, create, trash } from "ionicons/icons"
import { DataService } from "../shared/data.service"
import { Staff } from "../shared/models"
import { StaffModalComponent } from "./staff-modal.component"

@Component({
  selector: "app-staff",
  templateUrl: "./staff.page.html",
  styleUrls: ["./staff.page.scss"],
  standalone: true,
  imports: [
    CommonModule,
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonBackButton,
    IonButton,
    IonIcon,
    IonList,
    IonItemSliding,
    IonItem,
    IonLabel,
    IonItemOptions,
    IonItemOption,
  ],
})
export class StaffPage implements OnInit {
  staff: Staff[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {
    addIcons({ add, create, trash })
  }

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
        this.dataService.addStaff(result.data).subscribe({
          next: () => {
            this.showToast("Staff member added successfully")
          },
          error: (error) => {
            console.error("Add staff error:", error)
            this.showToast("Failed to add staff member", "danger")
          },
        })
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
        this.dataService.updateStaff(result.data).subscribe({
          next: () => {
            this.showToast("Staff member updated successfully")
          },
          error: (error) => {
            console.error("Update staff error:", error)
            this.showToast("Failed to update staff member", "danger")
          },
        })
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
            this.dataService.deleteStaff(id).subscribe({
              next: () => {
                this.showToast("Staff member deleted successfully")
              },
              error: (error) => {
                console.error("Delete staff error:", error)
                this.showToast("Failed to delete staff member", "danger")
              },
            })
          },
        },
      ],
    })
    await alert.present()
  }

  async showToast(message: string, color = "success") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }
}

export default StaffPage
