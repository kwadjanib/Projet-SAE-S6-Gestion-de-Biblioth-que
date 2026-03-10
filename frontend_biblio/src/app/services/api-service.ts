import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Livre } from '../models/livre';
import { Categorie } from '../models/categorie';
import { Adherent, Emprunt, Reservation } from '../models/adherent';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  private http = inject(HttpClient);

  private API_URL = 'http://127.0.0.1:8008/api';

  getLivres() {
    return this.http.get<Livre[]>(`${this.API_URL}/livres`);
  }

  getLivre(id: number) {
    return this.http.get<Livre>(`${this.API_URL}/livres/${id}`);
  }

  rechercherLivres(query: any) {
    return this.http.get<Livre[]>(`${this.API_URL}/livres/recherche`, {
      params: query
    });
  }

  getCategories() {
    return this.http.get<Categorie[]>(`${this.API_URL}/categories`);
  }



  reserverLivre(livreId: number) {
    return this.http.post<Reservation>(`${this.API_URL}/reservations`, { livreId });
  }

  annulerReservation(reservationId: number) {
    return this.http.delete<void>(`${this.API_URL}/reservations/${reservationId}`);
  }
}
