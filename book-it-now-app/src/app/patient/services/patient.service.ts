import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { ApiService } from "../../services/api.service"
import { Patient, PatientResponse } from "../models/patient.model"

@Injectable({
  providedIn: "root",
})
export class PatientService {
  constructor(private apiService: ApiService) {}

  getPatients(page = 1, search?: string): Observable<PatientResponse> {
    const params: any = { page }
    if (search) {
      params.search = search
    }
    return this.apiService.get<PatientResponse>("patients", params)
  }

  getPatient(id: number): Observable<Patient> {
    return this.apiService.get<Patient>(`patients/${id}`)
  }

  createPatient(patient: Patient): Observable<Patient> {
    return this.apiService.post<Patient>("patients", patient)
  }

  updatePatient(id: number, patient: Patient): Observable<Patient> {
    return this.apiService.put<Patient>(`patients/${id}`, patient)
  }

  deletePatient(id: number): Observable<any> {
    return this.apiService.delete(`patients/${id}`)
  }

  searchPatients(query: string): Observable<Patient[]> {
    return this.apiService.get<Patient[]>("patients/search", { q: query })
  }

  getPatientHistory(id: number): Observable<any[]> {
    return this.apiService.get<any[]>(`patients/${id}/history`)
  }

  getPatientAppointments(id: number): Observable<any[]> {
    return this.apiService.get<any[]>(`patients/${id}/appointments`)
  }
}
