import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ActivatedRoute, Router } from "@angular/router"
import { ToastController } from "@ionic/angular"
import type { QueueItem } from "../models/queue.model"
import { QueueService } from "../services/queue.service"

@Component({
  selector: "app-queue-form",
  templateUrl: "./queue-form.page.html",
  styleUrls: ["./queue-form.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class QueueFormPage implements OnInit {
  queueItem: QueueItem = {
    patientId: 1,
    queueNumber: 1,
    priority: "normal",
    status: "waiting",
    checkedInAt: new Date().toISOString(),
    notes: "",
  }

  isEdit = false
  loading = false

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private queueService: QueueService,
    private toastController: ToastController,
  ) {}

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get("id")
    if (id) {
      this.isEdit = true
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

  onSubmit() {
    this.loading = true

    if (this.isEdit && this.queueItem.id) {
      this.queueService.updateQueueItem(this.queueItem.id, this.queueItem).subscribe({
        next: () => {
          this.loading = false
          this.presentToast("Queue item updated successfully")
          this.router.navigate(["/queue/detail", this.queueItem.id])
        },
        error: (error) => {
          console.error("Error updating queue item:", error)
          this.loading = false
          this.presentToast("Error updating queue item", "danger")
        },
      })
    } else {
      this.queueService.addToQueue(this.queueItem).subscribe({
        next: (item) => {
          this.loading = false
          this.presentToast("Added to queue successfully")
          this.router.navigate(["/queue/detail", item.id])
        },
        error: (error) => {
          console.error("Error adding to queue:", error)
          this.loading = false
          this.presentToast("Error adding to queue", "danger")
        },
      })
    }
  }
}

export default QueueFormPage

