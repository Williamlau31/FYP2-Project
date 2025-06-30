import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { ApiService } from "../../services/api.service"
import { Appointment, AppointmentResponse, TimeSlot } from "../models/appointment.model"

@Injectable({
  providedIn: "root",
})
export class AppointmentService {
  constructor(private apiService: ApiService) {}

  getAppointments(page = 1, status?: string): Observable<AppointmentResponse> {
    const params: any = { page }
    if (status && status !== "all") {
      params.status = status
    }
    return this.apiService.get<AppointmentResponse>("appointments", params)
  }

  getAppointment(id: number): Observable<Appointment> {
    return this.apiService.get<Appointment>(`appointments/${id}`)
  }

  createAppointment(appointment: Appointment): Observable<Appointment> {
    return this.apiService.post<Appointment>("appointments", appointment)
  }

  updateAppointment(id: number, appointment: Appointment): Observable<Appointment> {
    return this.apiService.put<Appointment>(`appointments/${id}`, appointment)
  }

  deleteAppointment(id: number): Observable<any> {
    return this.apiService.delete(`appointments/${id}`)
  }

  checkAvailability(staffId: number, date: string): Observable<TimeSlot[]> {
    return this.apiService.get<TimeSlot[]>("appointments/availability", {
      staff_id: staffId,
      date: date,
    })
  }

  getUpcomingAppointments(): Observable<Appointment[]> {
    return this.apiService.get<Appointment[]>("appointments/upcoming")
  }

  getTodayAppointments(): Observable<Appointment[]> {
    return this.apiService.get<Appointment[]>("appointments/today")
  }

  updateStatus(id: number, status: string): Observable<Appointment> {
    return this.apiService.put<Appointment>(`appointments/${id}/status`, { status })
  }

  checkConflicts(appointment: Partial<Appointment>): Observable<any> {
    return this.apiService.post("appointments/check-conflicts", appointment)
  }
}

