import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { Appointment } from "../models/appointment.model"
import { MockDataService } from "../../services/mock-data.service"

@Injectable({
  providedIn: "root",
})
export class AppointmentService {
  constructor(private mockDataService: MockDataService) {}

  getAppointments(): Observable<Appointment[]> {
    return this.mockDataService.getAppointments()
  }

  getAppointment(id: number): Observable<Appointment> {
    return this.mockDataService.getAppointment(id)
  }

  createAppointment(appointment: Appointment): Observable<Appointment> {
    return this.mockDataService.createAppointment(appointment)
  }

  updateAppointment(id: number, appointment: Appointment): Observable<Appointment> {
    return this.mockDataService.updateAppointment(id, appointment)
  }

  deleteAppointment(id: number): Observable<any> {
    return this.mockDataService.deleteAppointment(id)
  }

  updateStatus(id: number, status: string): Observable<Appointment> {
    // Mock implementation
    return this.getAppointment(id)
  }
}

