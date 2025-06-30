import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ReportingService } from "../services/reporting.service"
import type { AppointmentReport } from "../models/report.model"

@Component({
  selector: "app-appointment-reports",
  templateUrl: "./appointment-reports.page.html",
  styleUrls: ["./appointment-reports.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class AppointmentReportsPage implements OnInit {
  public Math = Math;

  reports: AppointmentReport[] = []
  loading = false
  startDate = ""
  endDate = ""

  constructor(private reportingService: ReportingService) {}

  ngOnInit() {
    // Set default date range (last 7 days)
    const today = new Date()
    const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000)

    this.endDate = today.toISOString().split("T")[0]
    this.startDate = lastWeek.toISOString().split("T")[0]

    this.loadReports()
  }

  loadReports() {
    this.loading = true
    this.reportingService.getAppointmentReports(this.startDate, this.endDate).subscribe({
      next: (reports) => {
        this.reports = reports
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading reports:", error)
        this.loading = false
      },
    })
  }

  onDateRangeChange() {
    if (this.startDate && this.endDate) {
      this.loadReports()
    }
  }

  getTotalAppointments(): number {
    return this.reports.reduce((sum, report) => sum + report.totalAppointments, 0)
  }

  getTotalCompleted(): number {
    return this.reports.reduce((sum, report) => sum + report.completedAppointments, 0)
  }

  getTotalCancelled(): number {
    return this.reports.reduce((sum, report) => sum + report.cancelledAppointments, 0)
  }

  getTotalNoShow(): number {
    return this.reports.reduce((sum, report) => sum + report.noShowAppointments, 0)
  }

  getCompletionRate(): number {
    const total = this.getTotalAppointments()
    const completed = this.getTotalCompleted()
    return total > 0 ? Math.round((completed / total) * 100) : 0
  }
}

export default AppointmentReportsPage

