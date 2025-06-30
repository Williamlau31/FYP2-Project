export interface Staff {
  id?: number
  first_name: string
  last_name: string
  email: string
  phone: string
  role: "doctor" | "nurse" | "receptionist" | "admin" | "technician"
  department: string
  specialization?: string
  license_number?: string
  hire_date: string
  status: "active" | "inactive" | "on-leave"
  working_hours?: string
  created_at?: string
  updated_at?: string
}

export interface StaffResponse {
  data: Staff[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface StaffSchedule {
  staff_id: number
  date: string
  start_time: string
  end_time: string
  available: boolean
}

