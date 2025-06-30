import { NgModule } from "@angular/core"
import { CommonModule } from "@angular/common"
import { FormsModule } from "@angular/forms"
import { IonicModule } from "@ionic/angular"
import { RegisterPageRoutingModule } from "./register-routing.module"

@NgModule({
  imports: [CommonModule, FormsModule, IonicModule, RegisterPageRoutingModule],
  declarations: [], // Remove RegisterPage since it's standalone
})
export class RegisterPageModule {}

