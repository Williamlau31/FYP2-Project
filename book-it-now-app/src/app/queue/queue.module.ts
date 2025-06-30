import { NgModule } from "@angular/core"
import { RouterModule } from "@angular/router"

@NgModule({
  imports: [
    RouterModule.forChild([
      { path: "", redirectTo: "list", pathMatch: "full" },
      {
        path: "list",
        loadComponent: () => import("./queue-list/queue-list.page").then((m) => m.default),
      },
      {
        path: "detail/:id",
        loadComponent: () => import("./queue-detail/queue-detail.page").then((m) => m.default),
      },
      {
        path: "create",
        loadComponent: () => import("./queue-form/queue-form.page").then((m) => m.default),
      },
      {
        path: "edit/:id",
        loadComponent: () => import("./queue-form/queue-form.page").then((m) => m.default),
      },
    ]),
  ],
})
export class QueueModule {
  constructor() {
    console.log("⏰ QueueModule loaded")
  }
}


