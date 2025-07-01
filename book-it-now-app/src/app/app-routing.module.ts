import { NgModule } from "@angular/core"
import { PreloadAllModules, RouterModule, type Routes } from "@angular/router"

const routes: Routes = [
  {
    path: "home",
    loadChildren: () => import("./home/home.module").then((m) => m.HomePageModule),
  },
  {
    path: "login",
    loadChildren: () => import("./auth/login/login.module").then((m) => m.LoginPageModule),
  },
  {
    path: "appointments",
    loadChildren: () => import("./appointments/appointments.module").then((m) => m.AppointmentsPageModule),
  },
  {
    path: "patients",
    loadChildren: () => import("./patients/patients.module").then((m) => m.PatientsPageModule),
  },
  {
    path: "staff",
    loadChildren: () => import("./staff/staff.module").then((m) => m.StaffPageModule),
  },
  {
    path: "queue",
    loadChildren: () => import("./queue/queue.module").then((m) => m.QueuePageModule),
  },
  { path: "", redirectTo: "home", pathMatch: "full" },
]

@NgModule({
  imports: [RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })],
  exports: [RouterModule],
})
export class AppRoutingModule {}
