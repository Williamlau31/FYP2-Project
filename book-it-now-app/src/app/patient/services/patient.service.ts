import { Injectable } from "@angular/core"
import type { Observable } from "rxjs"
import type { Patient } from "../models/patient.model"
import type { ApiService } from "../../services/api.service"

@Injectable({
  providedIn: "root",
})
export class PatientService {
  constructor(private apiService: ApiService) {}

  getPatients(search?: string, page?: number, perPage?: number): Observable<any> {
    const params: any = {}
    if (search) params.search = search
    if (page) params.page = page
    if (perPage) params.per_page = perPage

    return this.apiService.get("patients", params)
  }

  getPatient(id: number): Observable<Patient> {
    return this.apiService.get<Patient>(`patients/${id}`)
  }

  createPatient(patient: Omit<Patient, "id">): Observable<Patient> {
    return this.apiService.post<Patient>("patients", patient)
  }

  updatePatient(id: number, patient: Partial<Patient>): Observable<Patient> {
    return this.apiService.put<Patient>(`patients/${id}`, patient)
  }

  deletePatient(id: number): Observable<any> {
    return this.apiService.delete(`patients/${id}`)
  }

  searchPatients(query: string): Observable<Patient[]> {
    return this.apiService.get<Patient[]>("patients/search", { q: query })
  }

  getPatientHistory(id: number): Observable<any> {
    return this.apiService.get(`patients/${id}/history`)
  }

  getPatientAppointments(id: number): Observable<any> {
    return this.apiService.get(`patients/${id}/appointments`)
  }

  exportPatients(format: "csv" | "pdf" = "csv"): Observable<Blob> {
    return this.apiService.get<Blob>(`patients/export`, { format })
  }
}
