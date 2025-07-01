import { NgModule } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { RouterModule } from "@angular/router"

import { PatientsPage } from "./patients.page"
import { PatientModalComponent } from "./patient-modal.component"

@NgModule({
  imports: [CommonModule, FormsModule, IonicModule, RouterModule.forChild([{ path: "", component: PatientsPage }])],
  declarations: [PatientsPage, PatientModalComponent],
})
export class PatientsPageModule {}
