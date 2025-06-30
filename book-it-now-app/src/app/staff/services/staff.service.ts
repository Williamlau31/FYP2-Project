import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { ApiService } from "../../services/api.service"
import { Staff, StaffResponse, StaffSchedule } from "../models/staff.model"

@Injectable({
  providedIn: "root",
})
export class StaffService {
  constructor(private apiService: ApiService) {}

  getStaff(page = 1, role?: string): Observable<StaffResponse> {
    const params: any = { page }
    if (role && role !== "all") {
      params.role = role
    }
    return this.apiService.get<StaffResponse>("staff", params)
  }

  getStaffMember(id: number): Observable<Staff> {
    return this.apiService.get<Staff>(`staff/${id}`)
  }

  createStaff(staff: Staff): Observable<Staff> {
    return this.apiService.post<Staff>("staff", staff)
  }

  updateStaff(id: number, staff: Staff): Observable<Staff> {
    return this.apiService.put<Staff>(`staff/${id}`, staff)
  }

  deleteStaff(id: number): Observable<any> {
    return this.apiService.delete(`staff/${id}`)
  }

  getStaffByRole(role: string): Observable<Staff[]> {
    return this.apiService.get<Staff[]>("staff/by-role", { role })
  }

  getStaffSchedule(id: number, date: string): Observable<StaffSchedule[]> {
    return this.apiService.get<StaffSchedule[]>(`staff/${id}/schedule`, { date })
  }

  updateStaffSchedule(id: number, schedule: StaffSchedule[]): Observable<any> {
    return this.apiService.put(`staff/${id}/schedule`, { schedule })
  }

  getAvailableStaff(date: string, time: string): Observable<Staff[]> {
    return this.apiService.get<Staff[]>("staff/available", { date, time })
  }

  searchStaff(query: string): Observable<Staff[]> {
    return this.apiService.get<Staff[]>("staff/search", { q: query })
  }
}

