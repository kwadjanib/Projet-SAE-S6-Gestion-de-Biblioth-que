import { Injectable, inject, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private http = inject(HttpClient);
  private router = inject(Router);
  private apiUrl = 'https://127.0.0.1:8008/api';
  private readonly tokenKey = 'jwt_token';
  private readonly userKey = 'user';

  isLoggedIn = signal(false);
  userEmail = signal('');
  userRoles = signal<string[]>([]);

  constructor() {
    const token = this.getToken();
    if (token) {
      this.isLoggedIn.set(true);
      const cachedUser = this.getUserFromStorage();
      if (cachedUser) {
        this.userEmail.set(cachedUser.email);
        this.userRoles.set(cachedUser.roles ?? []);
      }
      this.loadUserInfo();
    }
  }

  login(email: string, password: string) {
    return this.http.post<{ token: string }>(`${this.apiUrl}/login_check`, { email, password });
  }

  handleLoginSuccess(token: string) {
    localStorage.setItem(this.tokenKey, token);
    this.isLoggedIn.set(true);
    this.loadUserInfo();
  }

  logout() {
    localStorage.removeItem(this.tokenKey);
    localStorage.removeItem(this.userKey);
    this.isLoggedIn.set(false);
    this.userEmail.set('');
    this.userRoles.set([]);
    this.router.navigate(['/']);
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  isAdmin(): boolean {
    return this.userRoles().includes('ROLE_ADHERENT');
  }

  private loadUserInfo() {
    this.http.get<any>(`${this.apiUrl}/user/me`).subscribe({
      next: (user) => {
        this.userEmail.set(user.email);
        this.userRoles.set(user.roles);
        localStorage.setItem(this.userKey, JSON.stringify(user));
      },
      error: (err) => {
        if (err?.status === 401 || err?.status === 403) {
          this.logout();
        }
      }
    });
  }

  private getUserFromStorage(): { email: string; roles?: string[] } | null {
    const raw = localStorage.getItem(this.userKey);
    if (!raw) {
      return null;
    }
    try {
      return JSON.parse(raw);
    } catch {
      return null;
    }
  }
}
