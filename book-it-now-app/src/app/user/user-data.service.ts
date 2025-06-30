import { Injectable } from "@angular/core"
import type { HttpClient } from "@angular/common/http"
import { type Observable, BehaviorSubject } from "rxjs"
import { tap } from "rxjs/operators"
import { environment } from "../../environments/environment"
import type { User } from "../auth.service"

export interface UserPreferences {
  theme: "light" | "dark" | "auto"
  notifications: boolean
  language: string
  timezone: string
}

export interface UserActivity {
  id: number
  action: string
  description: string
  timestamp: string
  ip_address?: string
}

@Injectable({
  providedIn: "root",
})
export class UserDataService {
  private apiUrl = environment.apiUrl
  private userPreferencesSubject = new BehaviorSubject<UserPreferences | null>(null)
  public userPreferences$ = this.userPreferencesSubject.asObservable()

  constructor(private http: HttpClient) {
    this.loadStoredPreferences()
  }

  private loadStoredPreferences() {
    const preferences = localStorage.getItem("user_preferences")
    if (preferences) {
      this.userPreferencesSubject.next(JSON.parse(preferences))
    }
  }

  private getAuthHeaders() {
    const token = localStorage.getItem("auth_token")
    return {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    }
  }

  // User Profile Management
  updateUserProfile(userId: number, data: Partial<User>): Observable<User> {
    return this.http.put<User>(`${this.apiUrl}/users/${userId}`, data, {
      headers: this.getAuthHeaders(),
    })
  }

  uploadAvatar(userId: number, file: File): Observable<any> {
    const formData = new FormData()
    formData.append("avatar", file)

    const token = localStorage.getItem("auth_token")
    return this.http.post(`${this.apiUrl}/users/${userId}/avatar`, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
  }

  deleteAvatar(userId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/users/${userId}/avatar`, {
      headers: this.getAuthHeaders(),
    })
  }

  // User Preferences
  getUserPreferences(): Observable<UserPreferences> {
    return this.http
      .get<UserPreferences>(`${this.apiUrl}/user/preferences`, {
        headers: this.getAuthHeaders(),
      })
      .pipe(
        tap((preferences) => {
          localStorage.setItem("user_preferences", JSON.stringify(preferences))
          this.userPreferencesSubject.next(preferences)
        }),
      )
  }

  updateUserPreferences(preferences: Partial<UserPreferences>): Observable<UserPreferences> {
    return this.http
      .put<UserPreferences>(`${this.apiUrl}/user/preferences`, preferences, {
        headers: this.getAuthHeaders(),
      })
      .pipe(
        tap((updatedPreferences) => {
          localStorage.setItem("user_preferences", JSON.stringify(updatedPreferences))
          this.userPreferencesSubject.next(updatedPreferences)
        }),
      )
  }

  // User Activity Log
  getUserActivity(
    page = 1,
    limit = 20,
  ): Observable<{
    data: UserActivity[]
    total: number
    current_page: number
    last_page: number
  }> {
    return this.http.get<any>(`${this.apiUrl}/user/activity?page=${page}&limit=${limit}`, {
      headers: this.getAuthHeaders(),
    })
  }

  // Password Management
  changePassword(currentPassword: string, newPassword: string, confirmPassword: string): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/user/change-password`,
      {
        current_password: currentPassword,
        new_password: newPassword,
        new_password_confirmation: confirmPassword,
      },
      {
        headers: this.getAuthHeaders(),
      },
    )
  }

  // Account Management
  deactivateAccount(password: string): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/user/deactivate`,
      {
        password: password,
      },
      {
        headers: this.getAuthHeaders(),
      },
    )
  }

  deleteAccount(password: string): Observable<any> {
    return this.http.delete(`${this.apiUrl}/user/delete`, {
      headers: this.getAuthHeaders(),
      body: { password: password },
    })
  }

  // Two-Factor Authentication
  enableTwoFactor(): Observable<{ qr_code: string; secret: string }> {
    return this.http.post<any>(
      `${this.apiUrl}/user/2fa/enable`,
      {},
      {
        headers: this.getAuthHeaders(),
      },
    )
  }

  confirmTwoFactor(code: string): Observable<{ recovery_codes: string[] }> {
    return this.http.post<any>(
      `${this.apiUrl}/user/2fa/confirm`,
      {
        code: code,
      },
      {
        headers: this.getAuthHeaders(),
      },
    )
  }

  disableTwoFactor(password: string): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/user/2fa/disable`,
      {
        password: password,
      },
      {
        headers: this.getAuthHeaders(),
      },
    )
  }

  // Utility Methods
  getCurrentPreferences(): UserPreferences | null {
    return this.userPreferencesSubject.value
  }

  getDefaultPreferences(): UserPreferences {
    return {
      theme: "auto",
      notifications: true,
      language: "en",
      timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    }
  }
}
