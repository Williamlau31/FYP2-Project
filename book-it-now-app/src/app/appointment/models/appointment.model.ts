export interface Appointment {
  id?: number
  patient_id: number
  staff_id: number
  appointment_date: string
  appointment_time: string
  duration: number
  type: string
  status: "scheduled" | "confirmed" | "in-progress" | "completed" | "cancelled"
  notes?: string
  patient_name?: string
  staff_name?: string
  created_at?: string
  updated_at?: string
}

export interface AppointmentResponse {
  data: Appointment[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface TimeSlot {
  time: string
  available: boolean
}
