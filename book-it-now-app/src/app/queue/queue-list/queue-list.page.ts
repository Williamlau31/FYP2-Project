import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { QueueItem } from "../models/queue.model"
import { QueueService } from "../services/queue.service"

@Component({
  selector: "app-queue-list",
  templateUrl: "./queue-list.page.html",
  styleUrls: ["./queue-list.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class QueueListPage implements OnInit {
  queueItems: QueueItem[] = []
  loading = false

  constructor(
    private queueService: QueueService,
    private router: Router,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    this.loadQueue()
  }

  ionViewWillEnter() {
    this.loadQueue()
  }

  loadQueue() {
    this.loading = true
    this.queueService.getQueueItems().subscribe({
      next: (items) => {
        this.queueItems = items.sort((a, b) => a.queueNumber - b.queueNumber)
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading queue:", error)
        this.loading = false
        this.presentToast("Error loading queue", "danger")
      },
    })
  }

  async presentToast(message: string, color = "success") {
    const toast = await this.toastController.create({
      message,
      duration: 2000,
      color,
    })
    toast.present()
  }

  viewQueueItem(id: number) {
    this.router.navigate(["/queue/detail", id])
  }

  editQueueItem(id: number) {
    this.router.navigate(["/queue/edit", id])
  }

  async removeFromQueue(item: QueueItem) {
    const alert = await this.alertController.create({
      header: "Confirm Remove",
      message: `Remove ${item.patientName} from queue?`,
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
        },
        {
          text: "Remove",
          handler: () => {
            if (item.id) {
              this.queueService.removeFromQueue(item.id).subscribe({
                next: () => {
                  this.presentToast("Removed from queue successfully")
                  this.loadQueue()
                },
                error: (error) => {
                  console.error("Error removing from queue:", error)
                  this.presentToast("Error removing from queue", "danger")
                },
              })
            }
          },
        },
      ],
    })
    await alert.present()
  }

  addToQueue() {
    this.router.navigate(["/queue/create"])
  }

  getPriorityColor(priority: string): string {
    switch (priority) {
      case "urgent":
        return "danger"
      case "high":
        return "warning"
      case "normal":
        return "primary"
      case "low":
        return "medium"
      default:
        return "medium"
    }
  }

  getStatusColor(status: string): string {
    switch (status) {
      case "waiting":
        return "warning"
      case "called":
        return "primary"
      case "in-service":
        return "secondary"
      case "completed":
        return "success"
      case "no-show":
        return "danger"
      default:
        return "medium"
    }
  }
}

export default QueueListPage

