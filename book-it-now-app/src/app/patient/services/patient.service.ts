import { Injectable } from "@angular/core"
import { Observable } from "rxjs"
import { Patient } from "../models/patient.model"
import { MockDataService } from "../../services/mock-data.service"

@Injectable({
  providedIn: "root",
})
export class PatientService {
  constructor(private mockDataService: MockDataService) {}

  getPatients(): Observable<Patient[]> {
    return this.mockDataService.getPatients()
  }

  getPatient(id: number): Observable<Patient> {
    return this.mockDataService.getPatient(id)
  }

  createPatient(patient: Patient): Observable<Patient> {
    return this.mockDataService.createPatient(patient)
  }

  updatePatient(id: number, patient: Patient): Observable<Patient> {
    return this.mockDataService.updatePatient(id, patient)
  }

  deletePatient(id: number): Observable<any> {
    return this.mockDataService.deletePatient(id)
  }
}

