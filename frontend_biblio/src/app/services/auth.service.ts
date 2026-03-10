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
    this.clearSession(true);
  }

  getToken(): string | null {
    const token = localStorage.getItem(this.tokenKey);
    if (!token) {
      return null;
    }
    if (this.isTokenExpired(token)) {
      this.clearSession(false);
      return null;
    }
    return token;
  }

  isAdherent(): boolean {
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

  private clearSession(navigate: boolean) {
    localStorage.removeItem(this.tokenKey);
    localStorage.removeItem(this.userKey);
    this.isLoggedIn.set(false);
    this.userEmail.set('');
    this.userRoles.set([]);
    if (navigate) {
      this.router.navigate(['/']);
    }
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

  private isTokenExpired(token: string): boolean {
    const payload = this.decodeTokenPayload(token);
    if (!payload || typeof payload.exp !== 'number') {
      return true;
    }
    const now = Math.floor(Date.now() / 1000);
    return payload.exp <= now;
  }

  private decodeTokenPayload(token: string): { exp?: number } | null {
    const parts = token.split('.');
    if (parts.length !== 3) {
      return null;
    }
    const base64 = parts[1].replace(/-/g, '+').replace(/_/g, '/');
    const padded = base64.padEnd(base64.length + ((4 - (base64.length % 4)) % 4), '=');
    try {
      return JSON.parse(atob(padded));
    } catch {
      return null;
    }
  }
}
