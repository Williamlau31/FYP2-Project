export interface QueueItem {
  id?: number
  patientId: number
  appointmentId?: number
  queueNumber: number
  priority: "low" | "normal" | "high" | "urgent"
  status: "waiting" | "called" | "in-service" | "completed" | "no-show"
  estimatedWaitTime?: number
  checkedInAt: string
  calledAt?: string
  completedAt?: string
  patientName?: string
  notes?: string
  createdAt?: string
  updatedAt?: string
}
