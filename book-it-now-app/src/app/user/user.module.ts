import { NgModule } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule, ReactiveFormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { RouterModule } from "@angular/router"

import { UserProfileComponent } from "./user-profile/user-profile.component"

@NgModule({
  declarations: [UserProfileComponent],
  imports: [CommonModule, FormsModule, ReactiveFormsModule, IonicModule, RouterModule],
  exports: [UserProfileComponent],
})
export class UserModule {}
