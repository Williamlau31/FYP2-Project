import { Injectable } from "@angular/core"
import { HttpClient } from "@angular/common/http"
import { Observable, BehaviorSubject } from "rxjs"
import { tap } from "rxjs/operators"
import { environment } from "../environments/environment"

export interface User {
  id: number
  name: string
  email: string
  role?: string
  avatar?: string
  phone_number?: string
  address?: string
  dob?: string
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface LoginResponse {
  access_token: string
  token_type: string
  user: User
}

@Injectable({
  providedIn: "root",
})
export class AuthService {
  private apiUrl = environment.apiUrl
  private currentUserSubject = new BehaviorSubject<User | null>(null)
  public currentUser$ = this.currentUserSubject.asObservable()

  constructor(private http: HttpClient) {
    this.loadStoredUser()
  }

  private loadStoredUser() {
    const token = localStorage.getItem("auth_token")
    const user = localStorage.getItem("current_user")
    if (token && user) {
      this.currentUserSubject.next(JSON.parse(user))
    }
  }

  login(credentials: LoginCredentials): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${this.apiUrl}/login`, credentials).pipe(
      tap((response) => {
        localStorage.setItem("auth_token", response.access_token)
        localStorage.setItem("current_user", JSON.stringify(response.user))
        this.currentUserSubject.next(response.user)
      }),
    )
  }

  register(data: RegisterData): Observable<any> {
    return this.http.post(`${this.apiUrl}/register`, data)
  }

  logout(): Observable<any> {
    const token = localStorage.getItem("auth_token")
    return this.http
      .post(
        `${this.apiUrl}/logout`,
        {},
        {
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
          },
        },
      )
      .pipe(
        tap(() => {
          localStorage.removeItem("auth_token")
          localStorage.removeItem("current_user")
          this.currentUserSubject.next(null)
        }),
      )
  }

  getProfile(): Observable<User> {
    const token = localStorage.getItem("auth_token")
    return this.http.get<User>(`${this.apiUrl}/profile`, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    })
  }

  updateProfile(data: Partial<User>): Observable<User> {
    const token = localStorage.getItem("auth_token")
    return this.http
      .put<User>(`${this.apiUrl}/profile`, data, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      })
      .pipe(
        tap((user) => {
          localStorage.setItem("current_user", JSON.stringify(user))
          this.currentUserSubject.next(user)
        }),
      )
  }

  isLoggedIn(): boolean {
    return !!localStorage.getItem("auth_token")
  }

  getCurrentUser(): User | null {
    return this.currentUserSubject.value
  }

  isAdmin(): boolean {
    const user = this.getCurrentUser()
    return user?.role === "admin"
  }

  isUser(): boolean {
    const user = this.getCurrentUser()
    return user?.role === "user" || !user?.role
  }

  // Demo login methods for testing
  loginAsAdmin(): Observable<LoginResponse> {
    return this.login({ email: "admin@example.com", password: "password" })
  }

  loginAsUser(): Observable<LoginResponse> {
    return this.login({ email: "user@example.com", password: "password" })
  }
}

