import { Injectable } from "@angular/core"
import { HttpClient } from "@angular/common/http" // Fixed: removed 'type' keyword
import { BehaviorSubject, type Observable, tap, catchError, of } from "rxjs"
import { environment } from "../../environments/environment"

@Injectable({
  providedIn: "root",
})
export class AuthService {
  private currentUserSubject = new BehaviorSubject<any>(null)
  public currentUser$ = this.currentUserSubject.asObservable()
  private apiUrl = environment.apiUrl

  constructor(private http: HttpClient) {
    // Check if user is stored in localStorage
    const user = localStorage.getItem("currentUser")
    const token = localStorage.getItem("authToken")
    if (user && token) {
      this.currentUserSubject.next(JSON.parse(user))
    }
  }

  login(email: string, password: string): Observable<boolean> {
    return this.http.post<any>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap((response) => {
        if (response.user && response.token) {
          localStorage.setItem("currentUser", JSON.stringify(response.user))
          localStorage.setItem("authToken", response.token)
          this.currentUserSubject.next(response.user)
        }
      }),
      catchError((error) => {
        console.error("Login error:", error)
        return of(false)
      }),
    )
  }

  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/logout`, {}).pipe(
      tap(() => {
        localStorage.removeItem("currentUser")
        localStorage.removeItem("authToken")
        this.currentUserSubject.next(null)
      }),
      catchError((error) => {
        // Even if logout fails on server, clear local storage
        localStorage.removeItem("currentUser")
        localStorage.removeItem("authToken")
        this.currentUserSubject.next(null)
        return of(null)
      }),
    )
  }

  getCurrentUser() {
    return this.currentUserSubject.value
  }

  isLoggedIn(): boolean {
    return !!this.getCurrentUser() && !!localStorage.getItem("authToken")
  }

  isAdmin(): boolean {
    const user = this.getCurrentUser()
    return user?.role === "admin"
  }

  getAuthToken(): string | null {
    return localStorage.getItem("authToken")
  }

  getAuthHeaders() {
    const token = this.getAuthToken()
    return token ? { Authorization: `Bearer ${token}` } : {}
  }
}
