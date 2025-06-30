import { NgModule } from "@angular/core"
import { PreloadAllModules, RouterModule, Routes } from "@angular/router"

const routes: Routes = [
  {
    path: "home",
    loadComponent: () => import("./home/home.page").then((m) => m.default),
  },
  {
    path: "login",
    loadComponent: () => import("./user/login/login.page").then((m) => m.default),
  },
  {
    path: "register",
    loadChildren: () => import("./user/register/register.module").then((m) => m.RegisterPageModule),
  },
  {
    path: "user-profile",
    loadComponent: () => import("./user/user-profile/user-profile.page").then((m) => m.default),
  },
  {
    path: "appointment",
    loadChildren: () => import("./appointment/appointment.module").then((m) => m.AppointmentModule),
  },
  {
    path: "patient",
    loadChildren: () => import("./patient/patient.module").then((m) => m.PatientModule),
  },
  {
    path: "queue",
    loadChildren: () => import("./queue/queue.module").then((m) => m.QueueModule),
  },
  {
    path: "staff",
    loadChildren: () => import("./staff/staff.module").then((m) => m.StaffModule),
  },
  {
    path: "reporting",
    loadChildren: () => import("./reporting/reporting.module").then((m) => m.ReportingModule),
  },
  {
    path: "preview",
    loadComponent: () => import("./preview/preview.page").then((m) => m.default),
  },
  { path: "", redirectTo: "home", pathMatch: "full" },
]

@NgModule({
  imports: [RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })],
  exports: [RouterModule],
})
export class AppRoutingModule {}


