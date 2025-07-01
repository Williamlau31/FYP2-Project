import { Injectable } from "@angular/core"
import { HttpClient, HttpHeaders } from "@angular/common/http" // Fixed: removed 'type' keyword
import { type Observable, BehaviorSubject, tap, catchError, of } from "rxjs"
import type { Patient, Staff, Appointment, QueueItem } from "./models"
import { environment } from "../../environments/environment"
import { AuthService } from "./auth.service" // Fixed: removed 'type' keyword

@Injectable({
  providedIn: "root",
})
export class DataService {
  private apiUrl = environment.apiUrl

  // Local state subjects for real-time updates
  private patientsSubject = new BehaviorSubject<Patient[]>([])
  private staffSubject = new BehaviorSubject<Staff[]>([])
  private appointmentsSubject = new BehaviorSubject<Appointment[]>([])
  private queueSubject = new BehaviorSubject<QueueItem[]>([])

  constructor(
    private http: HttpClient,
    private authService: AuthService,
  ) {}

  private getHttpOptions() {
    const token = this.authService.getAuthToken()
    return {
      headers: new HttpHeaders({
        "Content-Type": "application/json",
        Authorization: token ? `Bearer ${token}` : "",
      }),
    }
  }

  private handleError<T>(operation = "operation", result?: T) {
    return (error: any): Observable<T> => {
      console.error(`${operation} failed:`, error)
      return of(result as T)
    }
  }

  // Patients
  getPatients(): Observable<Patient[]> {
    this.http
      .get<Patient[]>(`${this.apiUrl}/patients`, this.getHttpOptions())
      .pipe(
        tap((patients) => this.patientsSubject.next(patients)),
        catchError(this.handleError<Patient[]>("getPatients", [])),
      )
      .subscribe()

    return this.patientsSubject.asObservable()
  }

  addPatient(patient: Patient): Observable<Patient> {
    return this.http.post<Patient>(`${this.apiUrl}/patients`, patient, this.getHttpOptions()).pipe(
      tap((newPatient) => {
        const currentPatients = this.patientsSubject.value
        this.patientsSubject.next([...currentPatients, newPatient])
      }),
      catchError(this.handleError<Patient>("addPatient")),
    )
  }

  updatePatient(patient: Patient): Observable<Patient> {
    return this.http.put<Patient>(`${this.apiUrl}/patients/${patient.id}`, patient, this.getHttpOptions()).pipe(
      tap((updatedPatient) => {
        const currentPatients = this.patientsSubject.value
        const index = currentPatients.findIndex((p) => p.id === updatedPatient.id)
        if (index !== -1) {
          currentPatients[index] = updatedPatient
          this.patientsSubject.next([...currentPatients])
        }
      }),
      catchError(this.handleError<Patient>("updatePatient")),
    )
  }

  deletePatient(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/patients/${id}`, this.getHttpOptions()).pipe(
      tap(() => {
        const currentPatients = this.patientsSubject.value.filter((p) => p.id !== id)
        this.patientsSubject.next(currentPatients)
      }),
      catchError(this.handleError("deletePatient")),
    )
  }

  // Staff
  getStaff(): Observable<Staff[]> {
    this.http
      .get<Staff[]>(`${this.apiUrl}/staff`, this.getHttpOptions())
      .pipe(
        tap((staff) => this.staffSubject.next(staff)),
        catchError(this.handleError<Staff[]>("getStaff", [])),
      )
      .subscribe()

    return this.staffSubject.asObservable()
  }

  addStaff(staff: Staff): Observable<Staff> {
    return this.http.post<Staff>(`${this.apiUrl}/staff`, staff, this.getHttpOptions()).pipe(
      tap((newStaff) => {
        const currentStaff = this.staffSubject.value
        this.staffSubject.next([...currentStaff, newStaff])
      }),
      catchError(this.handleError<Staff>("addStaff")),
    )
  }

  updateStaff(staff: Staff): Observable<Staff> {
    return this.http.put<Staff>(`${this.apiUrl}/staff/${staff.id}`, staff, this.getHttpOptions()).pipe(
      tap((updatedStaff) => {
        const currentStaff = this.staffSubject.value
        const index = currentStaff.findIndex((s) => s.id === updatedStaff.id)
        if (index !== -1) {
          currentStaff[index] = updatedStaff
          this.staffSubject.next([...currentStaff])
        }
      }),
      catchError(this.handleError<Staff>("updateStaff")),
    )
  }

  deleteStaff(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/staff/${id}`, this.getHttpOptions()).pipe(
      tap(() => {
        const currentStaff = this.staffSubject.value.filter((s) => s.id !== id)
        this.staffSubject.next(currentStaff)
      }),
      catchError(this.handleError("deleteStaff")),
    )
  }

  // Appointments
  getAppointments(): Observable<Appointment[]> {
    this.http
      .get<Appointment[]>(`${this.apiUrl}/appointments`, this.getHttpOptions())
      .pipe(
        tap((appointments) => this.appointmentsSubject.next(appointments)),
        catchError(this.handleError<Appointment[]>("getAppointments", [])),
      )
      .subscribe()

    return this.appointmentsSubject.asObservable()
  }

  addAppointment(appointment: Appointment): Observable<Appointment> {
    return this.http.post<Appointment>(`${this.apiUrl}/appointments`, appointment, this.getHttpOptions()).pipe(
      tap((newAppointment) => {
        const currentAppointments = this.appointmentsSubject.value
        this.appointmentsSubject.next([...currentAppointments, newAppointment])
      }),
      catchError(this.handleError<Appointment>("addAppointment")),
    )
  }

  updateAppointment(appointment: Appointment): Observable<Appointment> {
    return this.http
      .put<Appointment>(`${this.apiUrl}/appointments/${appointment.id}`, appointment, this.getHttpOptions())
      .pipe(
        tap((updatedAppointment) => {
          const currentAppointments = this.appointmentsSubject.value
          const index = currentAppointments.findIndex((a) => a.id === updatedAppointment.id)
          if (index !== -1) {
            currentAppointments[index] = updatedAppointment
            this.appointmentsSubject.next([...currentAppointments])
          }
        }),
        catchError(this.handleError<Appointment>("updateAppointment")),
      )
  }

  deleteAppointment(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/appointments/${id}`, this.getHttpOptions()).pipe(
      tap(() => {
        const currentAppointments = this.appointmentsSubject.value.filter((a) => a.id !== id)
        this.appointmentsSubject.next(currentAppointments)
      }),
      catchError(this.handleError("deleteAppointment")),
    )
  }

  // Queue
  getQueue(): Observable<QueueItem[]> {
    this.http
      .get<QueueItem[]>(`${this.apiUrl}/queue`, this.getHttpOptions())
      .pipe(
        tap((queue) => this.queueSubject.next(queue)),
        catchError(this.handleError<QueueItem[]>("getQueue", [])),
      )
      .subscribe()

    return this.queueSubject.asObservable()
  }

  addToQueue(item: QueueItem): Observable<QueueItem> {
    return this.http.post<QueueItem>(`${this.apiUrl}/queue`, item, this.getHttpOptions()).pipe(
      tap((newItem) => {
        const currentQueue = this.queueSubject.value
        this.queueSubject.next([...currentQueue, newItem])
      }),
      catchError(this.handleError<QueueItem>("addToQueue")),
    )
  }

  updateQueueItem(item: QueueItem): Observable<QueueItem> {
    return this.http.put<QueueItem>(`${this.apiUrl}/queue/${item.id}`, item, this.getHttpOptions()).pipe(
      tap((updatedItem) => {
        const currentQueue = this.queueSubject.value
        const index = currentQueue.findIndex((q) => q.id === updatedItem.id)
        if (index !== -1) {
          currentQueue[index] = updatedItem
          this.queueSubject.next([...currentQueue])
        }
      }),
      catchError(this.handleError<QueueItem>("updateQueueItem")),
    )
  }

  removeFromQueue(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/queue/${id}`, this.getHttpOptions()).pipe(
      tap(() => {
        const currentQueue = this.queueSubject.value.filter((q) => q.id !== id)
        this.queueSubject.next(currentQueue)
      }),
      catchError(this.handleError("removeFromQueue")),
    )
  }

  // Utility methods to refresh data
  refreshPatients(): void {
    this.getPatients()
  }

  refreshStaff(): void {
    this.getStaff()
  }

  refreshAppointments(): void {
    this.getAppointments()
  }

  refreshQueue(): void {
    this.getQueue()
  }

  refreshAll(): void {
    this.refreshPatients()
    this.refreshStaff()
    this.refreshAppointments()
    this.refreshQueue()
  }
}
