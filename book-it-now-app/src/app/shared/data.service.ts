import { Injectable } from "@angular/core"
import { BehaviorSubject, type Observable } from "rxjs"
import type { Patient, Staff, Appointment, QueueItem } from "./models"

@Injectable({
  providedIn: "root",
})
export class DataService {
  // Mock data storage
  private patients: Patient[] = [
    { id: 1, name: "John Doe", email: "john@example.com", phone: "123-456-7890", address: "123 Main St" },
    { id: 2, name: "Jane Smith", email: "jane@example.com", phone: "098-765-4321", address: "456 Oak Ave" },
  ]

  private staff: Staff[] = [
    { id: 1, name: "Dr. Wilson", email: "wilson@clinic.com", role: "Doctor", department: "General" },
    { id: 2, name: "Nurse Johnson", email: "johnson@clinic.com", role: "Nurse", department: "General" },
  ]

  private appointments: Appointment[] = [
    {
      id: 1,
      patient_id: 1,
      staff_id: 1,
      date: "2024-01-15",
      time: "10:00",
      status: "scheduled",
      patient_name: "John Doe",
      staff_name: "Dr. Wilson",
    },
  ]

  private queue: QueueItem[] = [{ id: 1, patient_id: 1, queue_number: 1, status: "waiting", patient_name: "John Doe" }]

  private patientsSubject = new BehaviorSubject<Patient[]>(this.patients)
  private staffSubject = new BehaviorSubject<Staff[]>(this.staff)
  private appointmentsSubject = new BehaviorSubject<Appointment[]>(this.appointments)
  private queueSubject = new BehaviorSubject<QueueItem[]>(this.queue)

  // Patients
  getPatients(): Observable<Patient[]> {
    return this.patientsSubject.asObservable()
  }

  addPatient(patient: Patient): void {
    patient.id = this.patients.length + 1
    this.patients.push(patient)
    this.patientsSubject.next([...this.patients])
  }

  updatePatient(patient: Patient): void {
    const index = this.patients.findIndex((p) => p.id === patient.id)
    if (index !== -1) {
      this.patients[index] = patient
      this.patientsSubject.next([...this.patients])
    }
  }

  deletePatient(id: number): void {
    this.patients = this.patients.filter((p) => p.id !== id)
    this.patientsSubject.next([...this.patients])
  }

  // Staff
  getStaff(): Observable<Staff[]> {
    return this.staffSubject.asObservable()
  }

  addStaff(staff: Staff): void {
    staff.id = this.staff.length + 1
    this.staff.push(staff)
    this.staffSubject.next([...this.staff])
  }

  updateStaff(staff: Staff): void {
    const index = this.staff.findIndex((s) => s.id === staff.id)
    if (index !== -1) {
      this.staff[index] = staff
      this.staffSubject.next([...this.staff])
    }
  }

  deleteStaff(id: number): void {
    this.staff = this.staff.filter((s) => s.id !== id)
    this.staffSubject.next([...this.staff])
  }

  // Appointments
  getAppointments(): Observable<Appointment[]> {
    return this.appointmentsSubject.asObservable()
  }

  addAppointment(appointment: Appointment): void {
    appointment.id = this.appointments.length + 1
    // Add patient and staff names
    const patient = this.patients.find((p) => p.id === appointment.patient_id)
    const staff = this.staff.find((s) => s.id === appointment.staff_id)
    appointment.patient_name = patient?.name
    appointment.staff_name = staff?.name

    this.appointments.push(appointment)
    this.appointmentsSubject.next([...this.appointments])
  }

  updateAppointment(appointment: Appointment): void {
    const index = this.appointments.findIndex((a) => a.id === appointment.id)
    if (index !== -1) {
      this.appointments[index] = appointment
      this.appointmentsSubject.next([...this.appointments])
    }
  }

  deleteAppointment(id: number): void {
    this.appointments = this.appointments.filter((a) => a.id !== id)
    this.appointmentsSubject.next([...this.appointments])
  }

  // Queue
  getQueue(): Observable<QueueItem[]> {
    return this.queueSubject.asObservable()
  }

  addToQueue(item: QueueItem): void {
    item.id = this.queue.length + 1
    item.queue_number = this.queue.length + 1
    const patient = this.patients.find((p) => p.id === item.patient_id)
    item.patient_name = patient?.name

    this.queue.push(item)
    this.queueSubject.next([...this.queue])
  }

  updateQueueItem(item: QueueItem): void {
    const index = this.queue.findIndex((q) => q.id === item.id)
    if (index !== -1) {
      this.queue[index] = item
      this.queueSubject.next([...this.queue])
    }
  }

  removeFromQueue(id: number): void {
    this.queue = this.queue.filter((q) => q.id !== id)
    this.queueSubject.next([...this.queue])
  }
}
