export interface Patient {
  id?: number
  firstName: string
  lastName: string
  email: string
  phone: string
  dateOfBirth: string
  address: string
  emergencyContact: string
  emergencyPhone: string
  medicalHistory?: string
  allergies?: string
  createdAt?: string
  updatedAt?: string
}
