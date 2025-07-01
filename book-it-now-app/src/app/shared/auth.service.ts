import { Injectable } from "@angular/core"
import { BehaviorSubject } from "rxjs"

@Injectable({
  providedIn: "root",
})
export class AuthService {
  private currentUserSubject = new BehaviorSubject<any>(null)
  public currentUser$ = this.currentUserSubject.asObservable()

  constructor() {
    // Check if user is stored in localStorage
    const user = localStorage.getItem("currentUser")
    if (user) {
      this.currentUserSubject.next(JSON.parse(user))
    }
  }

  login(email: string, password: string): boolean {
    // Simple mock authentication
    if (email === "admin@test.com" && password === "admin") {
      const user = { id: 1, name: "Admin User", email: "admin@test.com", role: "admin" }
      localStorage.setItem("currentUser", JSON.stringify(user))
      this.currentUserSubject.next(user)
      return true
    } else if (email === "user@test.com" && password === "user") {
      const user = { id: 2, name: "Regular User", email: "user@test.com", role: "user" }
      localStorage.setItem("currentUser", JSON.stringify(user))
      this.currentUserSubject.next(user)
      return true
    }
    return false
  }

  logout() {
    localStorage.removeItem("currentUser")
    this.currentUserSubject.next(null)
  }

  getCurrentUser() {
    return this.currentUserSubject.value
  }

  isLoggedIn(): boolean {
    return !!this.getCurrentUser()
  }

  isAdmin(): boolean {
    const user = this.getCurrentUser()
    return user?.role === "admin"
  }
}
