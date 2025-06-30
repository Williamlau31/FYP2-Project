export interface Appointment {
  id?: number
  patientId: number
  staffId: number
  appointmentDate: string
  appointmentTime: string
  duration: number
  type: string
  status: "scheduled" | "confirmed" | "in-progress" | "completed" | "cancelled"
  notes?: string
  patientName?: string
  staffName?: string
  createdAt?: string
  updatedAt?: string
}
