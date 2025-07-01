import type { Routes } from "@angular/router"

export const routes: Routes = [
  {
    path: "home",
    loadComponent: () => import("./home/home.page").then((m) => m.default),
  },
  {
    path: "login",
    loadComponent: () => import("./auth/login/login.page").then((m) => m.default),
  },
  {
    path: "appointments",
    loadComponent: () => import("./appointments/appointments.page").then((m) => m.AppointmentsPage),
  },
  {
    path: "patients",
    loadComponent: () => import("./patients/patients.page").then((m) => m.PatientsPage),
  },
  {
    path: "staff",
    loadComponent: () => import("./staff/staff.page").then((m) => m.StaffPage),
  },
  {
    path: "queue",
    loadComponent: () => import("./queue/queue.page").then((m) => m.QueuePage),
  },
  { path: "", redirectTo: "home", pathMatch: "full" },
]
