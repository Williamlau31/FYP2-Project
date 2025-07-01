import { Component, type OnInit } from "@angular/core"
import type { ModalController, AlertController, ToastController, ActionSheetController } from "@ionic/angular"
import type { DataService } from "../shared/data.service"
import type { QueueItem } from "../shared/models"
import { QueueModalComponent } from "./queue-modal.component"

@Component({
  selector: "app-queue",
  templateUrl: "./queue.page.html",
  styleUrls: ["./queue.page.scss"],
})
export class QueuePage implements OnInit {
  queue: QueueItem[] = []

  constructor(
    private dataService: DataService,
    private modalController: ModalController,
    private alertController: AlertController,
    private toastController: ToastController,
    private actionSheetController: ActionSheetController,
  ) {}

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
        this.dataService.addToQueue(result.data)
        this.showToast("Patient added to queue successfully")
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
            item.status = "called"
            this.dataService.updateQueueItem(item)
            this.showToast("Status updated to Called")
          },
        },
        {
          text: "Completed",
          handler: () => {
            item.status = "completed"
            this.dataService.updateQueueItem(item)
            this.showToast("Status updated to Completed")
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
            this.dataService.removeFromQueue(id)
            this.showToast("Patient removed from queue")
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

  async showToast(message: string) {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color: "success",
    })
    toast.present()
  }
}

export default QueuePage
