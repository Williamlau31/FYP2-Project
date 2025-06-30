export interface Staff {
  id?: number
  firstName: string
  lastName: string
  email: string
  phone: string
  role: "doctor" | "nurse" | "receptionist" | "admin" | "technician"
  department: string
  specialization?: string
  licenseNumber?: string
  hireDate: string
  status: "active" | "inactive" | "on-leave"
  workingHours?: string
  createdAt?: string
  updatedAt?: string
}
