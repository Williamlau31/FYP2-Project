import { NgModule } from "@angular/core"
import { RouterModule } from "@angular/router"

@NgModule({
  imports: [
    RouterModule.forChild([
      { path: "", redirectTo: "list", pathMatch: "full" },
      {
        path: "list",
        loadComponent: () => import("./patient-list/patient-list.page").then((m) => m.default),
      },
      {
        path: "detail/:id",
        loadComponent: () => import("./patient-detail/patient-detail.page").then((m) => m.default),
      },
      {
        path: "create",
        loadComponent: () => import("./patient-form/patient-form.page").then((m) => m.default),
      },
      {
        path: "edit/:id",
        loadComponent: () => import("./patient-form/patient-form.page").then((m) => m.default),
      },
    ]),
  ],
})
export class PatientModule {
  constructor() {
    console.log("📋 PatientModule loaded")
  }
}


