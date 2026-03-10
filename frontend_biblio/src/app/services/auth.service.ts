import { inject, Injectable, signal, computed } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { tap } from 'rxjs/operators';
import { Adherent, AuthResponse, LoginDto } from '../models/adherent';

@Injectable({ providedIn: 'root' })
export class AuthService {

  private http = inject(HttpClient);
  private router = inject(Router);

  private API_URL = 'http://localhost:8008/api';

  private _token = signal<string | null>(localStorage.getItem('jwt_token'));
  private _user = signal<Adherent | null>(this.chargerUserStorage());

  readonly token = this._token.asReadonly();
  readonly user = this._user.asReadonly();
  readonly estConnecte = computed(() => !!this._token());

  private chargerUserStorage(): Adherent | null {
    const raw = localStorage.getItem('user');
    if (!raw) return null;
    try { return JSON.parse(raw); } catch { return null; }
  }

  login(credentials: LoginDto) {
    return this.http.post<AuthResponse>(`${this.API_URL}/login_check`, credentials).pipe(
      tap(res => {

        localStorage.setItem('jwt_token', res.token);
        this._token.set(res.token);
        this.http.get<Adherent>(`${this.API_URL}/adherent/profil`).subscribe(user => {
          localStorage.setItem('user', JSON.stringify(user));
          this._user.set(user);
        });
      })
    );
  }

  logout() {
    localStorage.removeItem('jwt_token');
    localStorage.removeItem('user');
    this._token.set(null);
    this._user.set(null);
    this.router.navigate(['/']);
  }

  getToken(): string | null {
    return this._token();
  }
  
  mettreAJourUser(user: Adherent) {
    localStorage.setItem('user', JSON.stringify(user));
    this._user.set(user);
  }
}
