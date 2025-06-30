import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import type { Router } from "@angular/router"
import type { QueueService } from "../services/queue.service"
import type { QueueItem } from "../models/queue.model"

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
  ) {}

  ngOnInit() {
    this.loadQueue()
  }

  loadQueue() {
    this.loading = true
    this.queueService.getCurrentQueue().subscribe({
      next: (items: QueueItem[]) => {
        this.queueItems = items.sort((a: QueueItem, b: QueueItem) => a.queue_number - b.queue_number)
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading queue:", error)
        this.loading = false
      },
    })
  }

  callNext() {
    this.queueService.callNext().subscribe({
      next: () => {
        this.loadQueue()
      },
      error: (error) => {
        console.error("Error calling next:", error)
      },
    })
  }

  viewQueueItem(id: number) {
    this.router.navigate(["/queue/detail", id])
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
