import { Injectable } from "@angular/core"
import  { Observable } from "rxjs"
import  { ApiService } from "../../services/api.service"
import  {
  Report,
  DashboardStats,
  AppointmentReport,
  PatientReport,
  StaffReport,
  QueueReport,
  RevenueReport,
} from "../models/report.model"

@Injectable({
  providedIn: "root",
})
export class ReportingService {
  constructor(private apiService: ApiService) {}

  getDashboardStats(): Observable<DashboardStats> {
    return this.apiService.get<DashboardStats>("reports/dashboard")
  }

  getReports(page = 1, type?: string): Observable<{ data: Report[] }> {
    const params: any = { page }
    if (type && type !== "all") {
      params.type = type
    }
    return this.apiService.get<{ data: Report[] }>("reports", params)
  }

  getReport(id: number): Observable<Report> {
    return this.apiService.get<Report>(`reports/${id}`)
  }

  generateReport(type: string, parameters: any): Observable<Report> {
    return this.apiService.post<Report>("reports/generate", { type, parameters })
  }

  deleteReport(id: number): Observable<any> {
    return this.apiService.delete(`reports/${id}`)
  }

  getAppointmentReports(startDate: string, endDate: string): Observable<AppointmentReport[]> {
    return this.apiService.get<AppointmentReport[]>("reports/appointments", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getPatientReports(startDate: string, endDate: string): Observable<PatientReport[]> {
    return this.apiService.get<PatientReport[]>("reports/patients", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getStaffReports(startDate: string, endDate: string): Observable<StaffReport[]> {
    return this.apiService.get<StaffReport[]>("reports/staff", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getQueueReports(startDate: string, endDate: string): Observable<QueueReport[]> {
    return this.apiService.get<QueueReport[]>("reports/queue", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getRevenueReports(startDate: string, endDate: string): Observable<RevenueReport[]> {
    return this.apiService.get<RevenueReport[]>("reports/revenue", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  exportReport(id: number, format: "pdf" | "excel" | "csv"): Observable<Blob> {
    return this.apiService.get<Blob>(`reports/${id}/export`, { format })
  }

  scheduleReport(reportConfig: any): Observable<any> {
    return this.apiService.post("reports/schedule", reportConfig)
  }
}

