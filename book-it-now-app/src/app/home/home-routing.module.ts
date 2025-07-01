import { NgModule } from "@angular/core"
import { Routes, RouterModule } from "@angular/router"

const routes: Routes = [
  {
    path: "",
    loadComponent: () => import("./home.page").then((m) => m.default),
  },
]

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class HomePageRoutingModule {}
