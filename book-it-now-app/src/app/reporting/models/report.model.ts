export interface DashboardStats {
  totalPatients: number
  totalAppointments: number
  todayAppointments: number
  queueLength: number
  activeStaff: number
}

export interface AppointmentReport {
  date: string
  totalAppointments: number
  completedAppointments: number
  cancelledAppointments: number
  noShowAppointments: number
}

export interface PatientReport {
  month: string
  newPatients: number
  returningPatients: number
  totalVisits: number
}
