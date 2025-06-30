import { Injectable } from "@angular/core"
import type { Observable } from "rxjs"
import type { Staff } from "../models/staff.model"
import type { ApiService } from "../../services/api.service"

@Injectable({
  providedIn: "root",
})
export class StaffService {
  constructor(private apiService: ApiService) {}

  getStaff(filters?: any): Observable<any> {
    return this.apiService.get("staff", filters)
  }

  getStaffMember(id: number): Observable<Staff> {
    return this.apiService.get<Staff>(`staff/${id}`)
  }

  createStaff(staff: Omit<Staff, "id">): Observable<Staff> {
    return this.apiService.post<Staff>("staff", staff)
  }

  updateStaff(id: number, staff: Partial<Staff>): Observable<Staff> {
    return this.apiService.put<Staff>(`staff/${id}`, staff)
  }

  deleteStaff(id: number): Observable<any> {
    return this.apiService.delete(`staff/${id}`)
  }

  getStaffByRole(role: string): Observable<Staff[]> {
    return this.apiService.get<Staff[]>("staff/by-role", { role })
  }

  getAvailableStaff(date: string, time: string): Observable<Staff[]> {
    return this.apiService.get<Staff[]>("staff/available", { date, time })
  }

  getStaffSchedule(id: number, date?: string): Observable<any> {
    const params: any = {}
    if (date) params.date = date

    return this.apiService.get(`staff/${id}/schedule`, params)
  }

  updateStaffSchedule(id: number, schedule: any): Observable<any> {
    return this.apiService.put(`staff/${id}/schedule`, schedule)
  }

  getStaffAppointments(id: number, date?: string): Observable<any> {
    const params: any = {}
    if (date) params.date = date

    return this.apiService.get(`staff/${id}/appointments`, params)
  }

  searchStaff(query: string): Observable<Staff[]> {
    return this.apiService.get<Staff[]>("staff/search", { q: query })
  }

  getStaffWorkload(id: number, startDate: string, endDate: string): Observable<any> {
    return this.apiService.get(`staff/${id}/workload`, {
      start_date: startDate,
      end_date: endDate,
    })
  }

  updateStaffStatus(id: number, status: "active" | "inactive" | "on_leave"): Observable<Staff> {
    return this.apiService.put<Staff>(`staff/${id}/status`, { status })
  }

  getDoctors(): Observable<Staff[]> {
    return this.getStaffByRole("doctor")
  }

  getNurses(): Observable<Staff[]> {
    return this.getStaffByRole("nurse")
  }

  getReceptionists(): Observable<Staff[]> {
    return this.getStaffByRole("receptionist")
  }
}
