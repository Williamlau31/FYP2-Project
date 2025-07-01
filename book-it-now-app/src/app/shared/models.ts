// Simplified shared models
export interface User {
  id?: number
  name: string
  email: string
  role: "admin" | "user"
}

export interface Patient {
  id?: number
  name: string
  email: string
  phone: string
  address: string
}

export interface Staff {
  id?: number
  name: string
  email: string
  role: string
  department: string
}

export interface Appointment {
  id?: number
  patient_id: number
  staff_id: number
  date: string
  time: string
  status: "scheduled" | "completed" | "cancelled"
  notes?: string
  patient_name?: string
  staff_name?: string
}

export interface QueueItem {
  id?: number
  patient_id: number
  queue_number: number
  status: "waiting" | "called" | "completed"
  patient_name?: string
}
