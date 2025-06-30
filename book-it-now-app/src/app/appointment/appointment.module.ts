import { NgModule } from "@angular/core"
import { RouterModule } from "@angular/router"

@NgModule({
  imports: [
    RouterModule.forChild([
      { path: "", redirectTo: "list", pathMatch: "full" },
      {
        path: "list",
        loadComponent: () => import("./appointment-list/appointment-list.page").then((m) => m.default),
      },
      {
        path: "detail/:id",
        loadComponent: () => import("./appointment-detail/appointment-detail.page").then((m) => m.default),
      },
      {
        path: "create",
        loadComponent: () => import("./appointment-form/appointment-form.page").then((m) => m.default),
      },
      {
        path: "edit/:id",
        loadComponent: () => import("./appointment-form/appointment-form.page").then((m) => m.default),
      },
    ]),
  ],
})
export class AppointmentModule {
  constructor() {
    console.log("📅 AppointmentModule loaded")
  }
}


