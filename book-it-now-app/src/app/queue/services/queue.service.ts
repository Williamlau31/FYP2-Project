import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { ApiService } from "../../services/api.service"
import { QueueItem, QueueResponse, QueueStats } from "../models/queue.model"

@Injectable({
  providedIn: "root",
})
export class QueueService {
  constructor(private apiService: ApiService) {}

  getQueue(page = 1, status?: string): Observable<QueueResponse> {
    const params: any = { page }
    if (status && status !== "all") {
      params.status = status
    }
    return this.apiService.get<QueueResponse>("queue", params)
  }

  getQueueItem(id: number): Observable<QueueItem> {
    return this.apiService.get<QueueItem>(`queue/${id}`)
  }

  addToQueue(queueItem: Partial<QueueItem>): Observable<QueueItem> {
    return this.apiService.post<QueueItem>("queue", queueItem)
  }

  updateQueueItem(id: number, queueItem: Partial<QueueItem>): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}`, queueItem)
  }

  removeFromQueue(id: number): Observable<any> {
    return this.apiService.delete(`queue/${id}`)
  }

  callNext(): Observable<QueueItem> {
    return this.apiService.post<QueueItem>("queue/call-next", {})
  }

  updateStatus(id: number, status: string): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}/status`, { status })
  }

  getQueueStats(): Observable<QueueStats> {
    return this.apiService.get<QueueStats>("queue/stats")
  }

  getCurrentQueue(): Observable<QueueItem[]> {
    return this.apiService.get<QueueItem[]>("queue/current")
  }

  getWaitTime(queueNumber: number): Observable<{ estimated_wait_time: number }> {
    return this.apiService.get<{ estimated_wait_time: number }>(`queue/wait-time/${queueNumber}`)
  }

  resetQueue(): Observable<any> {
    return this.apiService.post("queue/reset", {})
  }
}

