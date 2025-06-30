import { Injectable } from "@angular/core"
import type { Observable } from "rxjs"
import type { DashboardStats } from "../models/report.model"
import type { ApiService } from "../../services/api.service"

@Injectable({
  providedIn: "root",
})
export class ReportingService {
  constructor(private apiService: ApiService) {}

  getDashboardStats(period?: string): Observable<DashboardStats> {
    const params: any = {}
    if (period) params.period = period

    return this.apiService.get<DashboardStats>("reports/dashboard", params)
  }

  getAppointmentReports(startDate: string, endDate: string, filters?: any): Observable<any> {
    const params = {
      start_date: startDate,
      end_date: endDate,
      ...filters,
    }

    return this.apiService.get("reports/appointments", params)
  }

  getPatientReports(year: number, month?: number): Observable<any> {
    const params: any = { year }
    if (month) params.month = month

    return this.apiService.get("reports/patients", params)
  }

  getStaffReports(startDate: string, endDate: string, staffId?: number): Observable<any> {
    const params: any = {
      start_date: startDate,
      end_date: endDate,
    }
    if (staffId) params.staff_id = staffId

    return this.apiService.get("reports/staff", params)
  }

  getQueueReports(startDate: string, endDate: string, department?: string): Observable<any> {
    const params: any = {
      start_date: startDate,
      end_date: endDate,
    }
    if (department) params.department = department

    return this.apiService.get("reports/queue", params)
  }

  getFinancialReports(startDate: string, endDate: string): Observable<any> {
    return this.apiService.get("reports/financial", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  exportReport(type: string, format: "pdf" | "csv" | "excel", params: any): Observable<Blob> {
    const exportParams = {
      type,
      format,
      ...params,
    }

    return this.apiService.get<Blob>("reports/export", exportParams)
  }

  getAppointmentTrends(period: "week" | "month" | "quarter" | "year"): Observable<any> {
    return this.apiService.get("reports/appointment-trends", { period })
  }

  getPatientDemographics(): Observable<any> {
    return this.apiService.get("reports/patient-demographics")
  }

  getStaffPerformance(startDate: string, endDate: string): Observable<any> {
    return this.apiService.get("reports/staff-performance", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getQueueAnalytics(startDate: string, endDate: string): Observable<any> {
    return this.apiService.get("reports/queue-analytics", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getRevenueTrends(period: "week" | "month" | "quarter" | "year"): Observable<any> {
    return this.apiService.get("reports/revenue-trends", { period })
  }

  getNoShowReports(startDate: string, endDate: string): Observable<any> {
    return this.apiService.get("reports/no-shows", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getCancellationReports(startDate: string, endDate: string): Observable<any> {
    return this.apiService.get("reports/cancellations", {
      start_date: startDate,
      end_date: endDate,
    })
  }

  getWaitTimeReports(startDate: string, endDate: string, department?: string): Observable<any> {
    const params: any = {
      start_date: startDate,
      end_date: endDate,
    }
    if (department) params.department = department

    return this.apiService.get("reports/wait-times", params)
  }

  getCustomReport(reportConfig: any): Observable<any> {
    return this.apiService.post("reports/custom", reportConfig)
  }

  saveReport(reportData: any): Observable<any> {
    return this.apiService.post("reports/save", reportData)
  }

  getSavedReports(): Observable<any> {
    return this.apiService.get("reports/saved")
  }

  deleteReport(id: number): Observable<any> {
    return this.apiService.delete(`reports/${id}`)
  }

  scheduleReport(reportConfig: any, schedule: any): Observable<any> {
    return this.apiService.post("reports/schedule", { ...reportConfig, schedule })
  }

  getScheduledReports(): Observable<any> {
    return this.apiService.get("reports/scheduled")
  }
}
