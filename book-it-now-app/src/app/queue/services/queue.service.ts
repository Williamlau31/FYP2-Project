import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { QueueItem } from "../models/queue.model"
import { MockDataService } from "../../services/mock-data.service"

@Injectable({
  providedIn: "root",
})
export class QueueService {
  constructor(private mockDataService: MockDataService) {}

  getQueueItems(): Observable<QueueItem[]> {
    return this.mockDataService.getQueueItems()
  }

  getQueueItem(id: number): Observable<QueueItem> {
    return this.mockDataService.getQueueItem(id)
  }

  addToQueue(queueItem: QueueItem): Observable<QueueItem> {
    return this.mockDataService.addToQueue(queueItem)
  }

  updateQueueItem(id: number, queueItem: QueueItem): Observable<QueueItem> {
    return this.mockDataService.updateQueueItem(id, queueItem)
  }

  removeFromQueue(id: number): Observable<any> {
    return this.mockDataService.removeFromQueue(id)
  }

  updateStatus(id: number, status: string): Observable<QueueItem> {
    // Mock implementation
    return this.getQueueItem(id)
  }

  callNext(): Observable<QueueItem> {
    // Mock implementation
    return this.getQueueItem(1)
  }
}

