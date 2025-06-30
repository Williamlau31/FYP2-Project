import { NgModule } from "@angular/core"
import { RouterModule } from "@angular/router"

@NgModule({
  imports: [
    RouterModule.forChild([
      { path: "", redirectTo: "list", pathMatch: "full" },
      {
        path: "list",
        loadComponent: () => import("./staff-list/staff-list.page").then((m) => m.default),
      },
      {
        path: "detail/:id",
        loadComponent: () => import("./staff-detail/staff-detail.page").then((m) => m.default),
      },
      {
        path: "create",
        loadComponent: () => import("./staff-form/staff-form.page").then((m) => m.default),
      },
      {
        path: "edit/:id",
        loadComponent: () => import("./staff-form/staff-form.page").then((m) => m.default),
      },
    ]),
  ],
})
export class StaffModule {
  constructor() {
    console.log("👥 StaffModule loaded")
  }
}
