export interface QueueItem {
  id?: number
  patient_id: number
  appointment_id?: number
  queue_number: number
  priority: "low" | "normal" | "high" | "urgent"
  status: "waiting" | "called" | "in-service" | "completed" | "no-show"
  checked_in_at: string
  called_at?: string
  completed_at?: string
  estimated_wait_time?: number
  notes?: string
  patient_name?: string
  created_at?: string
  updated_at?: string
}

export interface QueueResponse {
  data: QueueItem[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface QueueStats {
  total_waiting: number
  average_wait_time: number
  current_queue_length: number
  served_today: number
}

// Export Queue as alias for QueueItem for backward compatibility
export type Queue = QueueItem
