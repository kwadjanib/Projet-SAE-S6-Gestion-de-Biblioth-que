import { Component, inject, signal, OnInit } from '@angular/core';
import { DatePipe } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../services/api-service';
import { AuthService } from '../../services/auth.service';
import { Livre } from '../../models/livre';

@Component({
  selector: 'app-livres-list',
  imports: [DatePipe, RouterLink, FormsModule],
  templateUrl: './livres-list.html',
  styleUrl: './livres-list.css'
})
export class LivresList implements OnInit {

  private apiService = inject(ApiService);
  auth = inject(AuthService);

  livres = signal<Livre[]>([]);

  reservationEnCours = signal<number | null>(null);
  messages = signal<{ [livreId: number]: { texte: string; type: 'success' | 'danger' | 'warning' } }>({});

  titre = '';
  auteur = '';
  categorie = '';
  langue = '';
  dateMin = '';
  dateMax = '';

  ngOnInit() {
    this.chargerLivres();
  }

  rechercher() {
    const query: {
      titre?: string;
      auteur?: string;
      categorie?: string;
      langue?: string;
      dateMin?: string;
      dateMax?: string;
    } = {};
    if (this.titre.trim()) query.titre = this.titre.trim();
    if (this.auteur.trim()) query.auteur = this.auteur.trim();
    if (this.categorie.trim()) query.categorie = this.categorie.trim();
    if (this.langue.trim()) query.langue = this.langue.trim();
    if (this.dateMin) query.dateMin = this.dateMin;
    if (this.dateMax) query.dateMax = this.dateMax;
    console.log('Requête de recherche :', query);

    this.apiService.rechercherLivres(query).subscribe(data => {
      this.livres.set(data);
    });
  }

  reinitialiser() {
    this.titre = '';
    this.auteur = '';
    this.categorie = '';
    this.langue = '';
    this.dateMin = '';
    this.dateMax = '';
    this.chargerLivres();
  }

  private chargerLivres() {
    this.apiService.getLivres().subscribe(data => {
      this.livres.set(data);
    });
  }

  reserver(livreId: number) {
    this.reservationEnCours.set(livreId);
    this.messages.update(msgs => ({ ...msgs, [livreId]: undefined as any }));

    this.apiService.reserverLivre(livreId).subscribe({
      next: () => {
        this.reservationEnCours.set(null);
        this.messages.update(msgs => ({
          ...msgs,
          [livreId]: { texte: 'Réservation effectuée avec succès !', type: 'success' }
        }));
      },
      error: (err) => {
        this.reservationEnCours.set(null);
        const message = this.getMessage(err);
        this.messages.update(msgs => ({
          ...msgs,
          [livreId]: { texte: message, type: 'danger' }
        }));
      }
    });
  }

  private getMessage(err: any): string {
    const status = err.status;
    const detail = err.error?.message ?? err.error?.detail ?? '';

    if (status === 409) {
      if (detail.toLowerCase().includes('déjà réservé') || detail.toLowerCase().includes('already reserved')) {
        return 'Ce livre est déjà réservé par un autre adhérent.';
      }
      if (detail.toLowerCase().includes('emprunté') || detail.toLowerCase().includes('borrowed')) {
        return 'Ce livre est actuellement emprunté.';
      }
      if (detail.toLowerCase().includes('limite') || detail.toLowerCase().includes('maximum')) {
        return 'Vous avez atteint la limite de 3 réservations simultanées.';
      }
      return detail || 'Réservation impossible (conflit).';
    }

    if (status === 401) return 'Vous devez être connecté pour réserver.';
    if (status === 403) return 'Vous n\'êtes pas autorisé à effectuer cette action.';

    return detail || 'Une erreur est survenue. Veuillez réessayer.';
  }

  getMsg(livreId: number) {
    return this.messages()[livreId];
  }
}
