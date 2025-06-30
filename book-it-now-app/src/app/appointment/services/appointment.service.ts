import { Injectable } from "@angular/core"
import type { Observable } from "rxjs"
import type { Appointment } from "../models/appointment.model"
import type { ApiService } from "../../services/api.service"

@Injectable({
  providedIn: "root",
})
export class AppointmentService {
  constructor(private apiService: ApiService) {}

  getAppointments(filters?: any): Observable<any> {
    return this.apiService.get("appointments", filters)
  }

  getAppointment(id: number): Observable<Appointment> {
    return this.apiService.get<Appointment>(`appointments/${id}`)
  }

  createAppointment(appointment: Omit<Appointment, "id">): Observable<Appointment> {
    return this.apiService.post<Appointment>("appointments", appointment)
  }

  updateAppointment(id: number, appointment: Partial<Appointment>): Observable<Appointment> {
    return this.apiService.put<Appointment>(`appointments/${id}`, appointment)
  }

  deleteAppointment(id: number): Observable<any> {
    return this.apiService.delete(`appointments/${id}`)
  }

  updateStatus(id: number, status: string): Observable<Appointment> {
    return this.apiService.put<Appointment>(`appointments/${id}/status`, { status })
  }

  checkAvailability(date: string, time: string, staffId?: number): Observable<any> {
    const params: any = { date, time }
    if (staffId) params.staff_id = staffId

    return this.apiService.get("appointments/check-availability", params)
  }

  getAppointmentsByDate(date: string): Observable<Appointment[]> {
    return this.apiService.get<Appointment[]>("appointments/by-date", { date })
  }

  getAppointmentsByPatient(patientId: number): Observable<Appointment[]> {
    return this.apiService.get<Appointment[]>(`appointments/patient/${patientId}`)
  }

  getAppointmentsByStaff(staffId: number): Observable<Appointment[]> {
    return this.apiService.get<Appointment[]>(`appointments/staff/${staffId}`)
  }

  getTodaysAppointments(): Observable<Appointment[]> {
    return this.apiService.get<Appointment[]>("appointments/today")
  }

  getUpcomingAppointments(days = 7): Observable<Appointment[]> {
    return this.apiService.get<Appointment[]>("appointments/upcoming", { days })
  }

  rescheduleAppointment(id: number, newDate: string, newTime: string): Observable<Appointment> {
    return this.apiService.put<Appointment>(`appointments/${id}/reschedule`, {
      date: newDate,
      time: newTime,
    })
  }

  cancelAppointment(id: number, reason?: string): Observable<Appointment> {
    return this.apiService.put<Appointment>(`appointments/${id}/cancel`, { reason })
  }

  confirmAppointment(id: number): Observable<Appointment> {
    return this.apiService.put<Appointment>(`appointments/${id}/confirm`, {})
  }

  getConflicts(date: string, time: string, duration: number, excludeId?: number): Observable<any> {
    const params: any = { date, time, duration }
    if (excludeId) params.exclude_id = excludeId

    return this.apiService.get("appointments/conflicts", params)
  }
}
