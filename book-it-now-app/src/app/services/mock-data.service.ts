import { Injectable } from "@angular/core"
import { type Observable, of, delay, throwError } from "rxjs"
import { Patient } from "../patient/models/patient.model"
import { Appointment } from "../appointment/models/appointment.model"
import { QueueItem } from "../queue/models/queue.model"
import { Staff } from "../staff/models/staff.model"
import { DashboardStats } from "../reporting/models/report.model"

@Injectable({
  providedIn: "root",
})
export class MockDataService {
  // Mock Patients Data
  private mockPatients: Patient[] = [
    {
      id: 1,
      firstName: "John",
      lastName: "Doe",
      email: "john.doe@email.com",
      phone: "+1234567890",
      dateOfBirth: "1985-06-15",
      address: "123 Main St, City, State 12345",
      emergencyContact: "Jane Doe",
      emergencyPhone: "+1234567891",
      medicalHistory: "Hypertension, Diabetes Type 2",
      allergies: "Penicillin, Shellfish",
      createdAt: "2024-01-15T10:00:00Z",
    },
    {
      id: 2,
      firstName: "Sarah",
      lastName: "Johnson",
      email: "sarah.johnson@email.com",
      phone: "+1234567892",
      dateOfBirth: "1990-03-22",
      address: "456 Oak Ave, City, State 12345",
      emergencyContact: "Mike Johnson",
      emergencyPhone: "+1234567893",
      medicalHistory: "Asthma",
      allergies: "Pollen, Dust",
      createdAt: "2024-01-20T14:30:00Z",
    },
    {
      id: 3,
      firstName: "Michael",
      lastName: "Brown",
      email: "michael.brown@email.com",
      phone: "+1234567894",
      dateOfBirth: "1978-11-08",
      address: "789 Pine St, City, State 12345",
      emergencyContact: "Lisa Brown",
      emergencyPhone: "+1234567895",
      medicalHistory: "Previous heart surgery",
      allergies: "None known",
      createdAt: "2024-02-01T09:15:00Z",
    },
    {
      id: 4,
      firstName: "Emily",
      lastName: "Davis",
      email: "emily.davis@email.com",
      phone: "+1234567896",
      dateOfBirth: "1992-07-12",
      address: "321 Elm St, City, State 12345",
      emergencyContact: "Robert Davis",
      emergencyPhone: "+1234567897",
      medicalHistory: "Migraine headaches",
      allergies: "Latex",
      createdAt: "2024-02-05T11:20:00Z",
    },
    {
      id: 5,
      firstName: "David",
      lastName: "Wilson",
      email: "david.wilson@email.com",
      phone: "+1234567898",
      dateOfBirth: "1975-12-03",
      address: "654 Maple Ave, City, State 12345",
      emergencyContact: "Susan Wilson",
      emergencyPhone: "+1234567899",
      medicalHistory: "High cholesterol",
      allergies: "Nuts",
      createdAt: "2024-02-10T16:45:00Z",
    },
  ]

  // Mock Staff Data
  private mockStaff: Staff[] = [
    {
      id: 1,
      firstName: "Dr. Emily",
      lastName: "Wilson",
      email: "emily.wilson@clinic.com",
      phone: "+1234567896",
      role: "doctor",
      department: "Cardiology",
      specialization: "Interventional Cardiology",
      licenseNumber: "MD123456",
      hireDate: "2020-01-15",
      status: "active",
      workingHours: "8:00 AM - 5:00 PM",
    },
    {
      id: 2,
      firstName: "Nurse",
      lastName: "Patricia",
      email: "patricia.nurse@clinic.com",
      phone: "+1234567897",
      role: "nurse",
      department: "General",
      specialization: "Emergency Care",
      licenseNumber: "RN789012",
      hireDate: "2021-03-10",
      status: "active",
      workingHours: "7:00 AM - 7:00 PM",
    },
    {
      id: 3,
      firstName: "Dr. Robert",
      lastName: "Davis",
      email: "robert.davis@clinic.com",
      phone: "+1234567898",
      role: "doctor",
      department: "Orthopedics",
      specialization: "Sports Medicine",
      licenseNumber: "MD345678",
      hireDate: "2019-06-20",
      status: "active",
      workingHours: "9:00 AM - 6:00 PM",
    },
    {
      id: 4,
      firstName: "Dr. Sarah",
      lastName: "Martinez",
      email: "sarah.martinez@clinic.com",
      phone: "+1234567900",
      role: "doctor",
      department: "Pediatrics",
      specialization: "Child Development",
      licenseNumber: "MD456789",
      hireDate: "2022-01-15",
      status: "active",
      workingHours: "8:00 AM - 4:00 PM",
    },
    {
      id: 5,
      firstName: "Nurse",
      lastName: "Michael",
      email: "michael.nurse@clinic.com",
      phone: "+1234567901",
      role: "nurse",
      department: "Surgery",
      specialization: "Operating Room",
      licenseNumber: "RN567890",
      hireDate: "2021-08-20",
      status: "active",
      workingHours: "6:00 AM - 6:00 PM",
    },
  ]

  // Mock Appointments Data
  private mockAppointments: Appointment[] = [
    {
      id: 1,
      patientId: 1,
      staffId: 1,
      appointmentDate: "2024-01-30",
      appointmentTime: "10:00",
      duration: 30,
      type: "Consultation",
      status: "confirmed",
      notes: "Regular checkup",
      patientName: "John Doe",
      staffName: "Dr. Emily Wilson",
      createdAt: "2024-01-25T10:00:00Z",
    },
    {
      id: 2,
      patientId: 2,
      staffId: 2,
      appointmentDate: "2024-01-30",
      appointmentTime: "14:30",
      duration: 45,
      type: "Follow-up",
      status: "scheduled",
      notes: "Asthma follow-up",
      patientName: "Sarah Johnson",
      staffName: "Nurse Patricia",
      createdAt: "2024-01-26T11:00:00Z",
    },
    {
      id: 3,
      patientId: 3,
      staffId: 3,
      appointmentDate: "2024-01-31",
      appointmentTime: "09:15",
      duration: 60,
      type: "Surgery Consultation",
      status: "confirmed",
      notes: "Pre-surgery consultation",
      patientName: "Michael Brown",
      staffName: "Dr. Robert Davis",
      createdAt: "2024-01-27T09:00:00Z",
    },
    {
      id: 4,
      patientId: 4,
      staffId: 4,
      appointmentDate: "2024-02-01",
      appointmentTime: "11:00",
      duration: 30,
      type: "Check-up",
      status: "scheduled",
      notes: "Annual pediatric checkup",
      patientName: "Emily Davis",
      staffName: "Dr. Sarah Martinez",
      createdAt: "2024-01-28T14:00:00Z",
    },
    {
      id: 5,
      patientId: 5,
      staffId: 1,
      appointmentDate: "2024-02-02",
      appointmentTime: "15:30",
      duration: 45,
      type: "Follow-up",
      status: "completed",
      notes: "Cholesterol management follow-up",
      patientName: "David Wilson",
      staffName: "Dr. Emily Wilson",
      createdAt: "2024-01-29T16:00:00Z",
    },
  ]

  // Mock Queue Data
  private mockQueue: QueueItem[] = [
    {
      id: 1,
      patientId: 1,
      appointmentId: 1,
      queueNumber: 1,
      priority: "normal",
      status: "waiting",
      estimatedWaitTime: 15,
      checkedInAt: "2024-01-30T09:45:00Z",
      patientName: "John Doe",
      notes: "Regular appointment",
    },
    {
      id: 2,
      patientId: 2,
      appointmentId: 2,
      queueNumber: 2,
      priority: "high",
      status: "called",
      estimatedWaitTime: 5,
      checkedInAt: "2024-01-30T14:15:00Z",
      calledAt: "2024-01-30T14:25:00Z",
      patientName: "Sarah Johnson",
      notes: "Urgent follow-up",
    },
    {
      id: 3,
      patientId: 4,
      queueNumber: 3,
      priority: "normal",
      status: "waiting",
      estimatedWaitTime: 25,
      checkedInAt: "2024-02-01T10:30:00Z",
      patientName: "Emily Davis",
      notes: "Pediatric checkup",
    },
  ]

  // Mock Dashboard Stats
  private mockDashboardStats: DashboardStats = {
    totalPatients: 1250,
    totalAppointments: 3420,
    todayAppointments: 28,
    queueLength: 5,
    activeStaff: 12,
  }

  constructor() {
    console.log("🔧 MockDataService initialized with sample data")
    console.log(
      `📊 Loaded: ${this.mockPatients.length} patients, ${this.mockStaff.length} staff, ${this.mockAppointments.length} appointments`,
    )
  }

  // Patient Methods
  getPatients(): Observable<Patient[]> {
    console.log("📋 Fetching patients list")
    return of([...this.mockPatients]).pipe(delay(300))
  }

  getPatient(id: number): Observable<Patient> {
    console.log(`👤 Fetching patient with ID: ${id}`)
    const patient = this.mockPatients.find((p) => p.id === id)
    if (!patient) {
      return throwError(() => new Error(`Patient with ID ${id} not found`))
    }
    return of({ ...patient }).pipe(delay(200))
  }

  createPatient(patient: Patient): Observable<Patient> {
    console.log("➕ Creating new patient:", patient.firstName, patient.lastName)
    const newId = Math.max(...this.mockPatients.map((p) => p.id || 0)) + 1
    const newPatient = {
      ...patient,
      id: newId,
      createdAt: new Date().toISOString(),
    }
    this.mockPatients.push(newPatient)
    return of({ ...newPatient }).pipe(delay(500))
  }

  updatePatient(id: number, patient: Patient): Observable<Patient> {
    console.log(`✏️ Updating patient with ID: ${id}`)
    const index = this.mockPatients.findIndex((p) => p.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Patient with ID ${id} not found`))
    }
    this.mockPatients[index] = {
      ...patient,
      id,
      updatedAt: new Date().toISOString(),
    }
    return of({ ...this.mockPatients[index] }).pipe(delay(400))
  }

  deletePatient(id: number): Observable<any> {
    console.log(`🗑️ Deleting patient with ID: ${id}`)
    const index = this.mockPatients.findIndex((p) => p.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Patient with ID ${id} not found`))
    }
    this.mockPatients.splice(index, 1)
    return of({ success: true }).pipe(delay(300))
  }

  // Staff Methods
  getStaff(): Observable<Staff[]> {
    console.log("👥 Fetching staff list")
    return of([...this.mockStaff]).pipe(delay(300))
  }

  getStaffMember(id: number): Observable<Staff> {
    console.log(`👨‍⚕️ Fetching staff member with ID: ${id}`)
    const staff = this.mockStaff.find((s) => s.id === id)
    if (!staff) {
      return throwError(() => new Error(`Staff member with ID ${id} not found`))
    }
    return of({ ...staff }).pipe(delay(200))
  }

  createStaff(staff: Staff): Observable<Staff> {
    console.log("➕ Creating new staff member:", staff.firstName, staff.lastName)
    const newId = Math.max(...this.mockStaff.map((s) => s.id || 0)) + 1
    const newStaff = {
      ...staff,
      id: newId,
      createdAt: new Date().toISOString(),
    }
    this.mockStaff.push(newStaff)
    return of({ ...newStaff }).pipe(delay(500))
  }

  updateStaff(id: number, staff: Staff): Observable<Staff> {
    console.log(`✏️ Updating staff member with ID: ${id}`)
    const index = this.mockStaff.findIndex((s) => s.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Staff member with ID ${id} not found`))
    }
    this.mockStaff[index] = {
      ...staff,
      id,
      updatedAt: new Date().toISOString(),
    }
    return of({ ...this.mockStaff[index] }).pipe(delay(400))
  }

  deleteStaff(id: number): Observable<any> {
    console.log(`🗑️ Deleting staff member with ID: ${id}`)
    const index = this.mockStaff.findIndex((s) => s.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Staff member with ID ${id} not found`))
    }
    this.mockStaff.splice(index, 1)
    return of({ success: true }).pipe(delay(300))
  }

  // Appointment Methods
  getAppointments(): Observable<Appointment[]> {
    console.log("📅 Fetching appointments list")
    return of([...this.mockAppointments]).pipe(delay(300))
  }

  getAppointment(id: number): Observable<Appointment> {
    console.log(`📅 Fetching appointment with ID: ${id}`)
    const appointment = this.mockAppointments.find((a) => a.id === id)
    if (!appointment) {
      return throwError(() => new Error(`Appointment with ID ${id} not found`))
    }
    return of({ ...appointment }).pipe(delay(200))
  }

  createAppointment(appointment: Appointment): Observable<Appointment> {
    console.log("➕ Creating new appointment for patient:", appointment.patientId)
    const newId = Math.max(...this.mockAppointments.map((a) => a.id || 0)) + 1

    // Get patient and staff names for display
    const patient = this.mockPatients.find((p) => p.id === appointment.patientId)
    const staff = this.mockStaff.find((s) => s.id === appointment.staffId)

    const newAppointment = {
      ...appointment,
      id: newId,
      patientName: patient ? `${patient.firstName} ${patient.lastName}` : `Patient #${appointment.patientId}`,
      staffName: staff ? `${staff.firstName} ${staff.lastName}` : `Staff #${appointment.staffId}`,
      createdAt: new Date().toISOString(),
    }
    this.mockAppointments.push(newAppointment)
    return of({ ...newAppointment }).pipe(delay(500))
  }

  updateAppointment(id: number, appointment: Appointment): Observable<Appointment> {
    console.log(`✏️ Updating appointment with ID: ${id}`)
    const index = this.mockAppointments.findIndex((a) => a.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Appointment with ID ${id} not found`))
    }

    // Get patient and staff names for display
    const patient = this.mockPatients.find((p) => p.id === appointment.patientId)
    const staff = this.mockStaff.find((s) => s.id === appointment.staffId)

    this.mockAppointments[index] = {
      ...appointment,
      id,
      patientName: patient ? `${patient.firstName} ${patient.lastName}` : `Patient #${appointment.patientId}`,
      staffName: staff ? `${staff.firstName} ${staff.lastName}` : `Staff #${appointment.staffId}`,
      updatedAt: new Date().toISOString(),
    }
    return of({ ...this.mockAppointments[index] }).pipe(delay(400))
  }

  deleteAppointment(id: number): Observable<any> {
    console.log(`🗑️ Deleting appointment with ID: ${id}`)
    const index = this.mockAppointments.findIndex((a) => a.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Appointment with ID ${id} not found`))
    }
    this.mockAppointments.splice(index, 1)
    return of({ success: true }).pipe(delay(300))
  }

  // Queue Methods
  getQueueItems(): Observable<QueueItem[]> {
    console.log("⏰ Fetching queue items")
    return of([...this.mockQueue]).pipe(delay(300))
  }

  getQueueItem(id: number): Observable<QueueItem> {
    console.log(`⏰ Fetching queue item with ID: ${id}`)
    const queueItem = this.mockQueue.find((q) => q.id === id)
    if (!queueItem) {
      return throwError(() => new Error(`Queue item with ID ${id} not found`))
    }
    return of({ ...queueItem }).pipe(delay(200))
  }

  addToQueue(queueItem: QueueItem): Observable<QueueItem> {
    console.log("➕ Adding to queue:", queueItem.patientId)
    const newId = Math.max(...this.mockQueue.map((q) => q.id || 0)) + 1

    // Get patient name for display
    const patient = this.mockPatients.find((p) => p.id === queueItem.patientId)

    const newQueueItem = {
      ...queueItem,
      id: newId,
      patientName: patient ? `${patient.firstName} ${patient.lastName}` : `Patient #${queueItem.patientId}`,
      createdAt: new Date().toISOString(),
    }
    this.mockQueue.push(newQueueItem)
    return of({ ...newQueueItem }).pipe(delay(500))
  }

  updateQueueItem(id: number, queueItem: QueueItem): Observable<QueueItem> {
    console.log(`✏️ Updating queue item with ID: ${id}`)
    const index = this.mockQueue.findIndex((q) => q.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Queue item with ID ${id} not found`))
    }

    // Get patient name for display
    const patient = this.mockPatients.find((p) => p.id === queueItem.patientId)

    this.mockQueue[index] = {
      ...queueItem,
      id,
      patientName: patient ? `${patient.firstName} ${patient.lastName}` : `Patient #${queueItem.patientId}`,
      updatedAt: new Date().toISOString(),
    }
    return of({ ...this.mockQueue[index] }).pipe(delay(400))
  }

  removeFromQueue(id: number): Observable<any> {
    console.log(`🗑️ Removing from queue with ID: ${id}`)
    const index = this.mockQueue.findIndex((q) => q.id === id)
    if (index === -1) {
      return throwError(() => new Error(`Queue item with ID ${id} not found`))
    }
    this.mockQueue.splice(index, 1)
    return of({ success: true }).pipe(delay(300))
  }

  // Dashboard Methods
  getDashboardStats(): Observable<DashboardStats> {
    console.log("📊 Fetching dashboard statistics")
    // Update stats based on current mock data
    const updatedStats = {
      ...this.mockDashboardStats,
      totalPatients: this.mockPatients.length,
      totalAppointments: this.mockAppointments.length,
      queueLength: this.mockQueue.length,
      activeStaff: this.mockStaff.filter((s) => s.status === "active").length,
    }
    return of(updatedStats).pipe(delay(400))
  }
}


