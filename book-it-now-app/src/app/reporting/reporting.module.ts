import { NgModule } from "@angular/core"
import { RouterModule } from "@angular/router"

@NgModule({
  imports: [
    RouterModule.forChild([
      { path: "", redirectTo: "dashboard", pathMatch: "full" },
      {
        path: "dashboard",
        loadComponent: () => import("./reporting-dashboard/reporting-dashboard.page").then((m) => m.default),
      },
      {
        path: "appointments",
        loadComponent: () => import("./appointment-reports/appointment-reports.page").then((m) => m.default),
      },
      {
        path: "patients",
        loadComponent: () => import("./patient-reports/patient-reports.page").then((m) => m.default),
      },
    ]),
  ],
})
export class ReportingModule {
  constructor() {
    console.log("📊 ReportingModule loaded")
  }
}


