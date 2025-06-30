import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { AlertController, ToastController } from "@ionic/angular"
import type { QueueItem } from "../models/queue.model"
import { QueueService } from "../services/queue.service"

@Component({
  selector: "app-queue-detail",
  templateUrl: "./queue-detail.page.html",
  styleUrls: ["./queue-detail.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class QueueDetailPage implements OnInit {
  queueItem: QueueItem | null = null
  loading = false

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private queueService: QueueService,
    private alertController: AlertController,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.loadQueueItem(+id)
    }
  }

  loadQueueItem(id: number) {
    this.loading = true
    this.queueService.getQueueItem(id).subscribe({
      next: (item) => {
        this.queueItem = item
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading queue item:", error)
        this.loading = false
        this.presentToast("Error loading queue item", "danger")
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

  editQueueItem() {
    if (this.queueItem?.id) {
      this.router.navigate(["/queue/edit", this.queueItem.id])
    }
  }

  async removeFromQueue() {
    if (!this.queueItem?.id) return

    const alert = await this.alertController.create({
      header: "Confirm Remove",
      message: `Remove ${this.queueItem.patientName} from queue?`,
      buttons: [
        {
          text: "Cancel",
          role: "cancel",
        },
        {
          text: "Remove",
          handler: () => {
            if (this.queueItem?.id) {
              this.queueService.removeFromQueue(this.queueItem.id).subscribe({
                next: () => {
                  this.presentToast("Removed from queue successfully")
                  this.router.navigate(["/queue/list"])
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

export default QueueDetailPage

