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
  IonChip,
  IonItemOptions,
  IonItemOption,
  ModalController,
  AlertController,
  ToastController,
  ActionSheetController,
} from "@ionic/angular/standalone"
import { addIcons } from "ionicons"
import { add, checkmark, trash } from "ionicons/icons"
import { DataService } from "../shared/data.service"
import { QueueItem } from "../shared/models"
import { QueueModalComponent } from "./queue-modal.component"

@Component({
  selector: "app-queue",
  templateUrl: "./queue.page.html",
  styleUrls: ["./queue.page.scss"],
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
    IonChip,
    IonItemOptions,
    IonItemOption,
  ],
})
export class QueuePage implements OnInit {
  queue: QueueItem[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
    private actionSheetController: ActionSheetController,
  ) {
    addIcons({ add, checkmark, trash })
  }

  ngOnInit() {
    this.dataService.getQueue().subscribe((queue) => {
      this.queue = queue
    })
  }

  async openAddModal() {
    const modal = await this.modalController.create({
      component: QueueModalComponent,
    })

    modal.onDidDismiss().then((result) => {
      if (result.data) {
        this.dataService.addToQueue(result.data).subscribe({
          next: () => {
            this.showToast("Patient added to queue successfully")
          },
          error: (error) => {
            console.error("Add to queue error:", error)
            if (error.status === 422) {
              this.showToast("Patient is already in the queue", "warning")
            } else {
              this.showToast("Failed to add patient to queue", "danger")
            }
          },
        })
      }
    })

    return await modal.present()
  }

  async updateStatus(item: QueueItem) {
    const actionSheet = await this.actionSheetController.create({
      header: "Update Status",
      buttons: [
        {
          text: "Called",
          handler: () => {
            const updatedItem = { ...item, status: "called" as const }
            this.dataService.updateQueueItem(updatedItem).subscribe({
              next: () => {
                this.showToast("Status updated to Called")
              },
              error: (error) => {
                console.error("Update queue status error:", error)
                this.showToast("Failed to update status", "danger")
              },
            })
          },
        },
        {
          text: "Completed",
          handler: () => {
            const updatedItem = { ...item, status: "completed" as const }
            this.dataService.updateQueueItem(updatedItem).subscribe({
              next: () => {
                this.showToast("Status updated to Completed")
              },
              error: (error) => {
                console.error("Update queue status error:", error)
                this.showToast("Failed to update status", "danger")
              },
            })
          },
        },
        {
          text: "Cancel",
          role: "cancel",
        },
      ],
    })
    await actionSheet.present()
  }

  async removeFromQueue(id: number) {
    const alert = await this.alertController.create({
      header: "Confirm Remove",
      message: "Are you sure you want to remove this patient from the queue?",
      buttons: [
        { text: "Cancel", role: "cancel" },
        {
          text: "Remove",
          handler: () => {
            this.dataService.removeFromQueue(id).subscribe({
              next: () => {
                this.showToast("Patient removed from queue")
              },
              error: (error) => {
                console.error("Remove from queue error:", error)
                this.showToast("Failed to remove patient from queue", "danger")
              },
            })
          },
        },
      ],
    })
    await alert.present()
  }

  getStatusColor(status: string): string {
    switch (status) {
      case "waiting":
        return "warning"
      case "called":
        return "primary"
      case "completed":
        return "success"
      default:
        return "medium"
    }
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

export default QueuePage
