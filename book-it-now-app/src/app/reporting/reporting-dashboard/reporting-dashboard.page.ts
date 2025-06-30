import { Component, type OnInit } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { RouterModule } from "@angular/router"
import { ReportingService } from "../services/reporting.service"
import type { DashboardStats } from "../models/report.model"

@Component({
  selector: "app-reporting-dashboard",
  templateUrl: "./reporting-dashboard.page.html",
  styleUrls: ["./reporting-dashboard.page.scss"],
  standalone: true,
  imports: [CommonModule, FormsModule, IonicModule, RouterModule],
})
export class ReportingDashboardPage implements OnInit {
  stats: DashboardStats | null = null
  loading = false

  constructor(private reportingService: ReportingService) {}

  ngOnInit() {
    this.loadDashboardStats()
  }

  loadDashboardStats() {
    this.loading = true
    this.reportingService.getDashboardStats().subscribe({
      next: (stats) => {
        this.stats = stats
        this.loading = false
      },
      error: (error) => {
        console.error("Error loading dashboard stats:", error)
        this.loading = false
        // Mock data for demo
        this.stats = {
          totalPatients: 1250,
          totalAppointments: 3420,
          todayAppointments: 28,
          queueLength: 5,
          activeStaff: 12,
        }
      },
    })
  }
}

export default ReportingDashboardPage

