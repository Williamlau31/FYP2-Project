import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { Staff } from "../models/staff.model"
import { MockDataService } from "../../services/mock-data.service"

@Injectable({
  providedIn: "root",
})
export class StaffService {
  constructor(private mockDataService: MockDataService) {}

  getStaff(): Observable<Staff[]> {
    return this.mockDataService.getStaff()
  }

  getStaffMember(id: number): Observable<Staff> {
    return this.mockDataService.getStaffMember(id)
  }

  createStaff(staff: Staff): Observable<Staff> {
    return this.mockDataService.createStaff(staff)
  }

  updateStaff(id: number, staff: Staff): Observable<Staff> {
    return this.mockDataService.updateStaff(id, staff)
  }

  deleteStaff(id: number): Observable<any> {
    return this.mockDataService.deleteStaff(id)
  }

  getStaffByRole(role: string): Observable<Staff[]> {
    // Mock implementation - filter by role
    return this.getStaff()
  }
}

