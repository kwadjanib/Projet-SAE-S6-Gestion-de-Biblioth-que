import { Component, inject, OnInit, signal } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { DatePipe } from '@angular/common';
import { ApiService } from '../../services/api-service';
import { Livre } from '../../models/livre';
import { Auteur, AuteurLivre } from '../../models/auteur';

@Component({
  selector: 'app-livre-detail',
  imports: [DatePipe, RouterLink],
  templateUrl: './livre-detail.html',
  styleUrl: './livre-detail.css'
})
export class LivreDetail implements OnInit {
  private apiService = inject(ApiService);
  private route = inject(ActivatedRoute);

  livre = signal<Livre | null>(null);
  auteur = signal<Auteur | null>(null);
  autresLivres = signal<AuteurLivre[]>([]);
  loading = signal(true);
  error = signal<string | null>(null);

  ngOnInit() {
    const idParam = this.route.snapshot.paramMap.get('id');
    const id = idParam ? Number(idParam) : NaN;

    if (!id || Number.isNaN(id)) {
      this.error.set('Livre introuvable.');
      this.loading.set(false);
      return;
    }

    this.apiService.getLivre(id).subscribe({
      next: (livre) => {
        this.livre.set(livre);
        this.loading.set(false);

        const auteurId = livre.auteurs?.[0]?.id;
        if (auteurId) {
          this.apiService.getAuteur(auteurId).subscribe({
            next: (auteur) => {
              this.auteur.set(auteur);
              const autres = (auteur.livre ?? []).filter(l => l.id !== livre.id);
              this.autresLivres.set(autres);
            },
            error: () => {
              this.auteur.set(null);
              this.autresLivres.set([]);
            }
          });
        }
      },
      error: () => {
        this.error.set('Livre introuvable.');
        this.loading.set(false);
      }
    });
  }

  getCouverture(livre?: { photoCouverture?: string; id?: number } | null) {
    return livre?.photoCouverture || this.getFallbackCover(livre?.id);
  }

  getAuteurPhoto(auteur?: Auteur | null) {
    return auteur?.photo || '/images/author-placeholder.svg';
  }

  formatAuteur(auteur: { prenom?: string | null; nom?: string | null } | null | undefined) {
    const prenom = auteur?.prenom?.trim();
    const nom = auteur?.nom?.trim();
    return [prenom, nom].filter(Boolean).join(' ') || nom || 'Auteur';
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
