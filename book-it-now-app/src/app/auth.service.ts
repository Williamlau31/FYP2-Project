import { Injectable } from "@angular/core"
import { BehaviorSubject, Observable, tap, catchError, throwError } from "rxjs"
import type { HttpClient } from "@angular/common/http"
import { environment } from "../environments/environment"

export interface User {
  id: number
  name: string
  email: string
  role: "admin" | "user"
  avatar?: string
  phone_number?: string
  address?: string
  dob?: string
}

export interface LoginResponse {
  access_token: string
  token_type: string
  user: User
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
  phone_number?: string
  address?: string
  dob?: string
}

@Injectable({
  providedIn: "root",
})
export class AuthService {
  private currentUserSubject = new BehaviorSubject<User | null>(null)
  public currentUser$ = this.currentUserSubject.asObservable()
  private apiUrl = environment.apiUrl || "http://localhost:8000/api"

  constructor(private http: HttpClient) {
    console.log("🔐 AuthService initialized")
    // Check if user is already logged in
    const savedUser = localStorage.getItem("currentUser")
    const token = localStorage.getItem("auth_token")

    if (savedUser && token) {
      const user = JSON.parse(savedUser)
      this.currentUserSubject.next(user)
      console.log("👤 Restored user session:", user.name, user.role)
    }
  }

  register(userData: RegisterData): Observable<any> {
    return this.http.post(`${this.apiUrl}/register`, userData).pipe(
      tap((response: any) => {
        console.log("✅ Registration successful")
      }),
      catchError((error) => {
        console.log("❌ Registration failed:", error.error?.message || error.message)
        return throwError(() => error)
      }),
    )
  }

  login(email: string, password: string): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap((response: LoginResponse) => {
        // Store token and user data
        localStorage.setItem("auth_token", response.access_token)
        localStorage.setItem("currentUser", JSON.stringify(response.user))

        this.currentUserSubject.next(response.user)
        console.log("✅ Login successful:", response.user.name, response.user.role)
      }),
      catchError((error) => {
        console.log("❌ Login failed:", error.error?.message || error.message)
        return throwError(() => error)
      }),
    )
  }

  logout(): Observable<any> {
    const token = localStorage.getItem("auth_token")

    if (!token) {
      this.clearSession()
      return new Observable((observer) => {
        observer.next({ message: "Already logged out" })
        observer.complete()
      })
    }

    return this.http
      .post(
        `${this.apiUrl}/logout`,
        {},
        {
          headers: { Authorization: `Bearer ${token}` },
        },
      )
      .pipe(
        tap(() => {
          console.log("👋 User logged out")
          this.clearSession()
        }),
        catchError((error) => {
          console.log("⚠️ Logout error, clearing session anyway")
          this.clearSession()
          return throwError(() => error)
        }),
      )
  }

  getProfile(): Observable<User> {
    const token = localStorage.getItem("auth_token")
    return this.http
      .get<User>(`${this.apiUrl}/profile`, {
        headers: { Authorization: `Bearer ${token}` },
      })
      .pipe(
        tap((user: User) => {
          localStorage.setItem("currentUser", JSON.stringify(user))
          this.currentUserSubject.next(user)
        }),
      )
  }

  updateProfile(userData: Partial<User>): Observable<User> {
    const token = localStorage.getItem("auth_token")
    return this.http
      .put<User>(`${this.apiUrl}/profile`, userData, {
        headers: { Authorization: `Bearer ${token}` },
      })
      .pipe(
        tap((user: User) => {
          localStorage.setItem("currentUser", JSON.stringify(user))
          this.currentUserSubject.next(user)
          console.log("✅ Profile updated:", user.name)
        }),
      )
  }

  private clearSession(): void {
    localStorage.removeItem("currentUser")
    localStorage.removeItem("auth_token")
    this.currentUserSubject.next(null)
  }

  getCurrentUser(): User | null {
    return this.currentUserSubject.value
  }

  isLoggedIn(): boolean {
    const loggedIn = this.currentUserSubject.value !== null && localStorage.getItem("auth_token") !== null
    console.log("🔍 Checking login status:", loggedIn)
    return loggedIn
  }

  isAdmin(): boolean {
    const user = this.getCurrentUser()
    const isAdmin = user?.role === "admin"
    console.log("🛡️ Checking admin status:", isAdmin)
    return isAdmin
  }

  isUser(): boolean {
    const user = this.getCurrentUser()
    const isUser = user?.role === "user"
    console.log("👤 Checking user status:", isUser)
    return isUser
  }

  getToken(): string | null {
    return localStorage.getItem("auth_token")
  }
}
