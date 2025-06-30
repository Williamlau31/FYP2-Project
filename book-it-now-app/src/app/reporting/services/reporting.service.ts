import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { DashboardStats, AppointmentReport, PatientReport } from "../models/report.model"
import { MockDataService } from "../../services/mock-data.service"

@Injectable({
  providedIn: "root",
})
export class ReportingService {
  constructor(private mockDataService: MockDataService) {}

  getDashboardStats(): Observable<DashboardStats> {
    return this.mockDataService.getDashboardStats()
  }

  getAppointmentReports(startDate: string, endDate: string): Observable<AppointmentReport[]> {
    // Mock data for appointment reports
    const mockReports: AppointmentReport[] = [
      {
        date: "2024-01-29",
        totalAppointments: 25,
        completedAppointments: 22,
        cancelledAppointments: 2,
        noShowAppointments: 1,
      },
      {
        date: "2024-01-30",
        totalAppointments: 28,
        completedAppointments: 24,
        cancelledAppointments: 3,
        noShowAppointments: 1,
      },
    ]
    return new Observable((observer) => {
      setTimeout(() => {
        observer.next(mockReports)
        observer.complete()
      }, 500)
    })
  }

  getPatientReports(year: number): Observable<PatientReport[]> {
    // Mock data for patient reports
    const mockReports: PatientReport[] = [
      { month: "January", newPatients: 45, returningPatients: 120, totalVisits: 165 },
      { month: "February", newPatients: 38, returningPatients: 135, totalVisits: 173 },
      { month: "March", newPatients: 52, returningPatients: 142, totalVisits: 194 },
    ]
    return new Observable((observer) => {
      setTimeout(() => {
        observer.next(mockReports)
        observer.complete()
      }, 500)
    })
  }

  exportReport(type: string, format: string, params: any): Observable<Blob> {
    // Mock export functionality
    const mockBlob = new Blob(["Mock report data"], { type: "text/plain" })
    return new Observable((observer) => {
      setTimeout(() => {
        observer.next(mockBlob)
        observer.complete()
      }, 1000)
    })
  }
}

