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
  nbReservations = signal<number>(0);

  titre = '';
  auteur = '';
  categorie = '';
  langue = '';
  dateMin = '';
  dateMax = '';

  ngOnInit() {
    this.chargerLivres();
    this.chargerReservations();
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

  private chargerReservations() {
    if (!this.auth.isLoggedIn()) {
      this.nbReservations.set(0);
      return;
    }
    this.apiService.getMesReservations().subscribe({
      next: (data) => this.nbReservations.set(data.length),
      error: () => this.nbReservations.set(0)
    });
  }

  reserver(livreId: number) {
    this.reservationEnCours.set(livreId);
    this.messages.update(msgs => ({ ...msgs, [livreId]: undefined as any }));

    this.apiService.reserverLivre(livreId).subscribe({
      next: () => {
        this.reservationEnCours.set(null);
        this.nbReservations.update(count => count + 1);
        this.messages.update(msgs => ({
          ...msgs,
          [livreId]: { texte: 'Reservation effectuee avec succes !', type: 'success' }
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
      const lower = detail.toLowerCase();
      if (lower.includes('deja reserve') || lower.includes('already reserved')) {
        return 'Ce livre est deja reserve par un autre adherent.';
      }
      if (lower.includes('emprunte') || lower.includes('borrowed')) {
        return 'Ce livre est actuellement emprunte.';
      }
      if (lower.includes('limite') || lower.includes('maximum')) {
        return 'Vous avez atteint la limite de 3 reservations simultanees.';
      }
      return detail || 'Reservation impossible (conflit).';
    }

    if (status === 401) return 'Vous devez etre connecte pour reserver.';
    if (status === 403) return 'Vous n\'etes pas autorise a effectuer cette action.';

    return detail || 'Une erreur est survenue. Veuillez reessayer.';
  }

  getMsg(livreId: number) {
    return this.messages()[livreId];
  }

  getCouverture(livre: Livre) {
    return livre.photoCouverture || this.getFallbackCover(livre.id);
  }

  getAuteursLabel(livre: Livre) {
    const auteurs = livre.auteurs ?? [];
    if (!auteurs.length) return 'Auteur inconnu';
    return auteurs
      .map(a => this.formatAuteur(a))
      .filter(Boolean)
      .join(', ');
  }

  private formatAuteur(auteur: { prenom?: string | null; nom?: string | null }) {
    const prenom = auteur.prenom?.trim();
    const nom = auteur.nom?.trim();
    return [prenom, nom].filter(Boolean).join(' ') || nom || '';
  }

  private getFallbackCover(id?: number) {
    const covers = [
      '/images/covers/cover-1.svg',
      '/images/covers/cover-2.svg',
      '/images/covers/cover-3.svg',
      '/images/covers/cover-4.svg',
      '/images/covers/cover-5.svg',
      '/images/covers/cover-6.svg'
    ];
    if (!id) return covers[0];
    return covers[Math.abs(id) % covers.length];
  }
}
