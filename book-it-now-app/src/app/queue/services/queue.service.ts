import { Injectable } from "@angular/core"
import type { Observable } from "rxjs"
import type { QueueItem } from "../models/queue.model"
import type { ApiService } from "../../services/api.service"

@Injectable({
  providedIn: "root",
})
export class QueueService {
  constructor(private apiService: ApiService) {}

  getQueueItems(filters?: any): Observable<any> {
    return this.apiService.get("queue", filters)
  }

  getQueueItem(id: number): Observable<QueueItem> {
    return this.apiService.get<QueueItem>(`queue/${id}`)
  }

  addToQueue(queueItem: Omit<QueueItem, "id" | "queue_number" | "estimated_wait_time">): Observable<QueueItem> {
    return this.apiService.post<QueueItem>("queue", queueItem)
  }

  updateQueueItem(id: number, queueItem: Partial<QueueItem>): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}`, queueItem)
  }

  removeFromQueue(id: number): Observable<any> {
    return this.apiService.delete(`queue/${id}`)
  }

  updateStatus(
    id: number,
    status: "waiting" | "in_progress" | "completed" | "cancelled" | "no_show",
  ): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}/status`, { status })
  }

  callNext(department?: string): Observable<QueueItem | null> {
    const params: any = {}
    if (department) params.department = department

    return this.apiService.post<QueueItem | null>("queue/call-next", params)
  }

  getCurrentQueue(department?: string): Observable<QueueItem[]> {
    const params: any = {}
    if (department) params.department = department

    return this.apiService.get<QueueItem[]>("queue/current", params)
  }

  getQueueByDepartment(department: string): Observable<QueueItem[]> {
    return this.apiService.get<QueueItem[]>(`queue/department/${department}`)
  }

  getQueueByPriority(priority: "low" | "normal" | "high" | "urgent"): Observable<QueueItem[]> {
    return this.apiService.get<QueueItem[]>("queue/by-priority", { priority })
  }

  getPatientPosition(patientId: number): Observable<any> {
    return this.apiService.get(`queue/patient/${patientId}/position`)
  }

  getEstimatedWaitTime(patientId: number): Observable<any> {
    return this.apiService.get(`queue/patient/${patientId}/wait-time`)
  }

  getQueueStatistics(date?: string): Observable<any> {
    const params: any = {}
    if (date) params.date = date

    return this.apiService.get("queue/statistics", params)
  }

  transferToQueue(id: number, newDepartment: string): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}/transfer`, { department: newDepartment })
  }

  updatePriority(id: number, priority: "low" | "normal" | "high" | "urgent"): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}/priority`, { priority })
  }

  getQueueHistory(patientId: number): Observable<QueueItem[]> {
    return this.apiService.get<QueueItem[]>(`queue/patient/${patientId}/history`)
  }

  pauseQueue(id: number, reason?: string): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}/pause`, { reason })
  }

  resumeQueue(id: number): Observable<QueueItem> {
    return this.apiService.put<QueueItem>(`queue/${id}/resume`, {})
  }

  getAverageWaitTime(department?: string): Observable<any> {
    const params: any = {}
    if (department) params.department = department

    return this.apiService.get("queue/average-wait-time", params)
  }
}
