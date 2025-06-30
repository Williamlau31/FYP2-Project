export interface Report {
  id?: number
  title: string
  type: "appointment" | "patient" | "staff" | "queue" | "financial"
  data: any
  generated_at: string
  generated_by: number
  parameters?: any
  created_at?: string
  updated_at?: string
}

export interface DashboardStats {
  total_patients: number
  total_appointments: number
  total_staff: number
  appointments_today: number
  patients_today: number
  queue_length: number
  revenue_today: number
  revenue_month: number
}

export interface AppointmentReport {
  date: string
  total_appointments: number
  completed_appointments: number
  cancelled_appointments: number
  no_show_appointments: number
  completion_rate: number
}

export interface PatientReport {
  month: string
  new_patients: number
  returning_patients: number
  total_visits: number
  patient_satisfaction?: number
}

export interface StaffReport {
  staff_id: number
  staff_name: string
  department: string
  appointments_handled: number
  average_appointment_time: number
  patient_satisfaction?: number
}

export interface QueueReport {
  date: string
  total_patients: number
  average_wait_time: number
  peak_hours: string[]
  efficiency_score: number
}

export interface RevenueReport {
  period: string
  total_revenue: number
  appointments_revenue: number
  services_revenue: number
  growth_rate: number
}

