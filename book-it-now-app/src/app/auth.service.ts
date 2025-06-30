import { Injectable } from "@angular/core"
import { BehaviorSubject, Observable } from "rxjs"

export interface User {
  id: number
  name: string
  email: string
  role: "admin" | "user"
  avatar?: string
}

@Injectable({
  providedIn: "root",
})
export class AuthService {
  private currentUserSubject = new BehaviorSubject<User | null>(null)
  public currentUser$ = this.currentUserSubject.asObservable()

  // Mock users for testing
  private mockUsers: User[] = [
    {
      id: 1,
      name: "Dr. Admin Smith",
      email: "admin@bookitnow.com",
      role: "admin",
      avatar: "/placeholder.svg?height=100&width=100",
    },
    {
      id: 2,
      name: "John Patient",
      email: "user@bookitnow.com",
      role: "user",
      avatar: "/placeholder.svg?height=100&width=100",
    },
  ]

  constructor() {
    console.log("🔐 AuthService initialized")
    // Check if user is already logged in
    const savedUser = localStorage.getItem("currentUser")
    if (savedUser) {
      const user = JSON.parse(savedUser)
      this.currentUserSubject.next(user)
      console.log("👤 Restored user session:", user.name, user.role)
    }
  }

  login(email: string, password: string): Observable<User> {
    return new Observable((observer) => {
      // Simulate API call delay
      setTimeout(() => {
        const user = this.mockUsers.find((u) => u.email === email)

        if (user && (password === "admin123" || password === "user123")) {
          this.currentUserSubject.next(user)
          localStorage.setItem("currentUser", JSON.stringify(user))
          console.log("✅ Login successful:", user.name, user.role)
          observer.next(user)
          observer.complete()
        } else {
          console.log("❌ Login failed: Invalid credentials")
          observer.error({ message: "Invalid credentials" })
        }
      }, 1000)
    })
  }

  logout(): void {
    console.log("👋 User logged out")
    localStorage.removeItem("currentUser")
    this.currentUserSubject.next(null)
  }

  getCurrentUser(): User | null {
    return this.currentUserSubject.value
  }

  isLoggedIn(): boolean {
    const loggedIn = this.currentUserSubject.value !== null
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

  // Quick login methods for testing
  loginAsAdmin(): void {
    const admin = this.mockUsers[0]
    this.currentUserSubject.next(admin)
    localStorage.setItem("currentUser", JSON.stringify(admin))
    console.log("🛡️ Quick login as admin:", admin.name)
  }

  loginAsUser(): void {
    const user = this.mockUsers[1]
    this.currentUserSubject.next(user)
    localStorage.setItem("currentUser", JSON.stringify(user))
    console.log("👤 Quick login as user:", user.name)
  }
}


