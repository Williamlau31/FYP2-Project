import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { ReportingService } from "../services/reporting.service"
import type { PatientReport } from "../models/report.model"

@Component({
  selector: "app-patient-reports",
  templateUrl: "./patient-reports.page.html",
  styleUrls: ["./patient-reports.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule],
})
export class PatientReportsPage implements OnInit {
  public Math = Math;

  reports: PatientReport[] = []
  loading = false
  selectedYear = new Date().getFullYear()

  constructor(private reportingService: ReportingService) {}

  ngOnInit() {
    this.loadReports()
  }

  loadReports() {
    this.loading = true
    this.reportingService.getPatientReports(this.selectedYear).subscribe({
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

  onYearChange() {
    this.loadReports()
  }

  getTotalNewPatients(): number {
    return this.reports.reduce((sum, report) => sum + report.newPatients, 0)
  }

  getTotalReturningPatients(): number {
    return this.reports.reduce((sum, report) => sum + report.returningPatients, 0)
  }

  getTotalVisits(): number {
    return this.reports.reduce((sum, report) => sum + report.totalVisits, 0)
  }

  getNewPatientRate(): number {
    const total = this.getTotalVisits()
    const newPatients = this.getTotalNewPatients()
    return total > 0 ? Math.round((newPatients / total) * 100) : 0
  }
}

export default PatientReportsPage

