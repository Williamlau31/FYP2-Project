export interface Patient {
  id?: number
  first_name: string
  last_name: string
  email: string
  phone: string
  date_of_birth: string
  address: string
  emergency_contact: string
  emergency_phone: string
  medical_history?: string
  allergies?: string
  created_at?: string
  updated_at?: string
}

export interface PatientResponse {
  data: Patient[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}
