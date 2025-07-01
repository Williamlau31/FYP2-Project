import { NgModule } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { RouterModule } from "@angular/router"

import { AppointmentsPage } from "./appointments.page"
import { AppointmentModalComponent } from "./appointment-modal.component"

@NgModule({
  imports: [CommonModule, FormsModule, IonicModule, RouterModule.forChild([{ path: "", component: AppointmentsPage }])],
  declarations: [AppointmentsPage, AppointmentModalComponent],
})
export class AppointmentsPageModule {}
